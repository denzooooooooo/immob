<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\PropertyDetail;
use App\Models\PropertyMedia;
use App\Models\City;
use App\Models\Neighborhood;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PropertyController extends Controller
{
    public function index(Request $request)
    {
        $query = Property::with(['user', 'cityModel'])
            ->withCount(['views', 'favorites']);

        // Filtres
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('published', $request->status === 'published');
        }

        if ($request->filled('city')) {
            $query->where('city_id', $request->city);
        }

        if ($request->filled('agent')) {
            $query->where('user_id', $request->agent);
        }

        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }

        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Tri
        $sort = $request->sort ?? 'created_at';
        $direction = $request->direction ?? 'desc';
        $query->orderBy($sort, $direction);

        $properties = $query->paginate(20)->withQueryString();

        // Données pour les filtres
        $cities = City::all();
        $agents = \App\Models\User::where('role', 'agent')->get();

        return view('admin.properties.index', compact(
            'properties',
            'cities',
            'agents'
        ));
    }

    public function create()
    {
        $cities = City::with('neighborhoods')->get();
        return view('admin.properties.create', compact('cities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string|in:house,apartment,land,commercial,hotel',
            'status' => 'required|string|in:for_sale,for_rent,hotel_room',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|in:XAF,EUR,USD',
            'description' => 'required|string',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'surface_area' => 'required|numeric|min:0',
            'address' => 'required|string',
            'city' => 'required|string|exists:cities,slug',
            'neighborhood' => 'required|string|exists:neighborhoods,slug',
            'featured' => 'boolean',
            'published' => 'boolean',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:5120',
            'videos.*' => 'mimetypes:video/mp4,video/quicktime|max:102400',
            // Détails de la propriété
            'year_built' => 'nullable|integer|min:1900',
            'parking_spaces' => 'nullable|integer|min:0',
            'furnished' => 'boolean',
            'air_conditioning' => 'boolean',
            'swimming_pool' => 'boolean',
            'security_system' => 'boolean',
            'internet' => 'boolean',
            'garden' => 'boolean',
            'balcony' => 'boolean',
            'elevator' => 'boolean',
            'garage' => 'boolean',
            'terrace' => 'boolean',
        ]);

        DB::beginTransaction();

        try {
            // Créer la propriété
            $property = Property::create([
                'user_id' => $request->user()->id,
                'title' => $validated['title'],
                'slug' => Str::slug($validated['title']),
                'type' => $validated['type'],
                'status' => $validated['status'],
                'price' => $validated['price'],
                'currency' => $validated['currency'],
                'description' => $validated['description'],
                'bedrooms' => $validated['bedrooms'],
                'bathrooms' => $validated['bathrooms'],
                'surface_area' => $validated['surface_area'],
                'address' => $validated['address'],
                'city' => $validated['city'],
                'neighborhood' => $validated['neighborhood'],
                'featured' => $validated['featured'] ?? false,
                'published' => $validated['published'] ?? false,
            ]);

            // Créer les détails
            $property->details()->create([
                'year_built' => $validated['year_built'],
                'parking_spaces' => $validated['parking_spaces'],
                'furnished' => $validated['furnished'] ?? false,
                'air_conditioning' => $validated['air_conditioning'] ?? false,
                'swimming_pool' => $validated['swimming_pool'] ?? false,
                'security_system' => $validated['security_system'] ?? false,
                'internet' => $validated['internet'] ?? false,
                'garden' => $validated['garden'] ?? false,
                'balcony' => $validated['balcony'] ?? false,
                'elevator' => $validated['elevator'] ?? false,
                'garage' => $validated['garage'] ?? false,
                'terrace' => $validated['terrace'] ?? false,
            ]);

            // Gérer les images avec Spatie Media Library
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $property->addMedia($image)
                        ->withCustomProperties([
                            'order' => $index,
                            'is_featured' => $index === 0
                        ])
                        ->toMediaCollection('images');
                }
            }

            // Gérer les vidéos avec Spatie Media Library
            if ($request->hasFile('videos')) {
                foreach ($request->file('videos') as $index => $video) {
                    $property->addMedia($video)
                        ->withCustomProperties(['order' => $index])
                        ->toMediaCollection('videos');
                }
            }

            DB::commit();

            return redirect()
                ->route('admin.properties.show', $property)
                ->with('success', 'Propriété créée avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la création de la propriété.');
        }
    }

    public function show(Property $property)
    {
        $property->load(['user', 'details', 'media', 'views', 'favorites']);
        return view('admin.properties.show', compact('property'));
    }

    public function edit(Property $property)
    {
        $property->load(['details', 'media']);
        $cities = City::with('neighborhoods')->get();
        return view('admin.properties.edit', compact('property', 'cities'));
    }

    public function update(Request $request, Property $property)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string|in:house,apartment,land,commercial,hotel',
            'status' => 'required|string|in:for_sale,for_rent,hotel_room',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|in:XAF,EUR,USD',
            'description' => 'required|string',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'surface_area' => 'required|numeric|min:0',
            'address' => 'required|string',
            'city' => 'required|string',
            'neighborhood' => 'nullable|string',
            'featured' => 'boolean',
            'published' => 'boolean',
            'new_images.*' => 'image|mimes:jpeg,png,jpg|max:5120',
            'new_videos.*' => 'mimetypes:video/mp4,video/quicktime|max:102400',
            // Détails de la propriété
            'year_built' => 'nullable|integer|min:1900',
            'parking_spaces' => 'nullable|integer|min:0',
            'furnished' => 'boolean',
            'air_conditioning' => 'boolean',
            'swimming_pool' => 'boolean',
            'security_system' => 'boolean',
            'internet' => 'boolean',
            'garden' => 'boolean',
            'balcony' => 'boolean',
            'elevator' => 'boolean',
            'garage' => 'boolean',
            'terrace' => 'boolean',
        ]);

        DB::beginTransaction();

        try {
            // Mettre à jour la propriété
            $property->update([
                'title' => $validated['title'],
                'type' => $validated['type'],
                'status' => $validated['status'],
                'price' => $validated['price'],
                'currency' => $validated['currency'],
                'description' => $validated['description'],
                'bedrooms' => $validated['bedrooms'],
                'bathrooms' => $validated['bathrooms'],
                'surface_area' => $validated['surface_area'],
                'address' => $validated['address'],
                'city' => $validated['city'],
                'neighborhood' => $validated['neighborhood'],
                'featured' => $validated['featured'] ?? false,
                'published' => $validated['published'] ?? false,
            ]);

            // Mettre à jour les détails
            $property->details()->update([
                'year_built' => $validated['year_built'],
                'parking_spaces' => $validated['parking_spaces'],
                'furnished' => $validated['furnished'] ?? false,
                'air_conditioning' => $validated['air_conditioning'] ?? false,
                'swimming_pool' => $validated['swimming_pool'] ?? false,
                'security_system' => $validated['security_system'] ?? false,
                'internet' => $validated['internet'] ?? false,
                'garden' => $validated['garden'] ?? false,
                'balcony' => $validated['balcony'] ?? false,
                'elevator' => $validated['elevator'] ?? false,
                'garage' => $validated['garage'] ?? false,
                'terrace' => $validated['terrace'] ?? false,
            ]);

            // Supprimer les médias sélectionnés (Spatie)
            if ($request->has('delete_media')) {
                foreach ($request->delete_media as $mediaId) {
                    $media = $property->media()->find($mediaId);
                    if ($media) {
                        $media->delete();
                    }
                }
            }

            // Ajouter de nouvelles images (Spatie)
            if ($request->hasFile('new_images')) {
                $currentMediaCount = $property->getMedia('images')->count();
                foreach ($request->file('new_images') as $index => $image) {
                    $property->addMedia($image)
                        ->withCustomProperties([
                            'order' => $currentMediaCount + $index,
                            'is_featured' => false
                        ])
                        ->toMediaCollection('images');
                }
            }

            // Ajouter de nouvelles vidéos (Spatie)
            if ($request->hasFile('new_videos')) {
                $currentMediaCount = $property->getMedia('videos')->count();
                foreach ($request->file('new_videos') as $index => $video) {
                    $property->addMedia($video)
                        ->withCustomProperties(['order' => $currentMediaCount + $index])
                        ->toMediaCollection('videos');
                }
            }

            // Mettre à jour l'ordre des médias (Spatie)
            if ($request->has('media_order')) {
                foreach ($request->media_order as $id => $order) {
                    $media = $property->media()->find($id);
                    if ($media) {
                        $media->setCustomProperty('order', $order);
                        $media->save();
                    }
                }
            }

            DB::commit();

            return redirect()
                ->route('admin.properties.show', $property)
                ->with('success', 'Propriété mise à jour avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la mise à jour de la propriété.');
        }
    }

    public function destroy(Property $property)
    {
        DB::beginTransaction();

        try {
            // Supprimer tous les médias Spatie
            $property->clearMediaCollection('images');
            $property->clearMediaCollection('videos');

            // Supprimer la propriété (les relations seront supprimées automatiquement)
            $property->delete();

            DB::commit();

            return redirect()
                ->route('admin.properties.index')
                ->with('success', 'Propriété supprimée avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Une erreur est survenue lors de la suppression de la propriété.');
        }
    }

    public function toggleFeatured(Property $property)
    {
        $property->update(['featured' => !$property->featured]);
        return back()->with('success', 'Statut "mis en avant" modifié avec succès.');
    }

    public function togglePublished(Property $property)
    {
        $property->update(['published' => !$property->published]);
        return back()->with('success', 'Statut de publication modifié avec succès.');
    }

    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|in:delete,publish,unpublish,feature,unfeature',
            'properties' => 'required|array',
            'properties.*' => 'exists:properties,id',
        ]);

        $count = 0;

        DB::beginTransaction();

        try {
            switch ($validated['action']) {
                case 'delete':
                    foreach (Property::whereIn('id', $validated['properties'])->get() as $property) {
                        // Supprimer les médias Spatie
                        $property->clearMediaCollection('images');
                        $property->clearMediaCollection('videos');
                        $property->delete();
                        $count++;
                    }
                    break;

                case 'publish':
                    $count = Property::whereIn('id', $validated['properties'])
                        ->update(['published' => true]);
                    break;

                case 'unpublish':
                    $count = Property::whereIn('id', $validated['properties'])
                        ->update(['published' => false]);
                    break;

                case 'feature':
                    $count = Property::whereIn('id', $validated['properties'])
                        ->update(['featured' => true]);
                    break;

                case 'unfeature':
                    $count = Property::whereIn('id', $validated['properties'])
                        ->update(['featured' => false]);
                    break;
            }

            DB::commit();

            return back()->with('success', "{$count} propriétés modifiées avec succès.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Une erreur est survenue lors de l\'action groupée.');
        }
    }
}
