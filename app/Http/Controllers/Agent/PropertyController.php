<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\City;
use App\Models\Neighborhood;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PropertyController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = $user->properties()->with(['media', 'views']);
        
        // Filtres
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('published')) {
            $query->where('published', $request->published === 'true');
        }
        
        if ($request->filled('featured')) {
            $query->where('featured', $request->featured === 'true');
        }
        
        // Tri
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
        
        $properties = $query->paginate(12);
        
        // Statistiques
        $stats = [
            'total' => $user->properties()->count(),
            'published' => $user->properties()->where('published', true)->count(),
            'draft' => $user->properties()->where('published', false)->count(),
            'featured' => $user->properties()->where('featured', true)->count(),
        ];
        
        // Récupérer les villes pour le filtre
        $cities = City::active()->orderBy('name')->get();
        
        return view('agent.properties.index', compact('properties', 'stats', 'cities'));
    }

    public function create()
    {
        $user = Auth::user();

        // Vérifier si l'agent peut ajouter une propriété
        if (!$user->canPostProperty()) {
            return redirect()->route('agent.subscription.show')
                ->with('error', 'Vous devez avoir un abonnement actif pour ajouter des propriétés.');
        }

        $cities = City::active()->orderBy('name')->get();
        $neighborhoods = Neighborhood::orderBy('name')->get();

        return view('agent.properties.create', compact('cities', 'neighborhoods'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        
        // Vérifier si l'agent peut ajouter une propriété
        if (!$user->canPostProperty()) {
            return redirect()->back()
                ->with('error', 'Vous avez atteint la limite de propriétés de votre abonnement.');
        }
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:house,apartment,land,commercial,hotel',
            'status' => 'required|in:for_sale,for_rent,hotel_room',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|in:XAF,EUR,USD',
            'surface_area' => 'required|numeric|min:1',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'floor' => 'nullable|integer|min:0',
            'total_floors' => 'nullable|integer|min:1',
            'construction_year' => 'nullable|integer|min:1900|max:' . (date('Y') + 5),
            'energy_rating' => 'nullable|string|in:A,B,C,D,E,F,G',
            'furnished' => 'boolean',
            'parking' => 'boolean',
            'garden' => 'boolean',
            'pool' => 'boolean',
            'security' => 'boolean',
            'elevator' => 'boolean',
            'balcony' => 'boolean',
            'air_conditioning' => 'boolean',
            'nearby_amenities' => 'nullable|string',
            'address' => 'required|string|max:255',
            'city' => 'required|string',
            'neighborhood' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'featured' => 'boolean',
            'published' => 'boolean',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:5120', // 5MB max
        ]);
        
        $validated['user_id'] = $user->id;
        $validated['slug'] = Str::slug($validated['title']);
        
        $property = Property::create($validated);
        
        // Upload des images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('properties', 'public');
                
                $property->media()->create([
                    'type' => 'image',
                    'path' => 'storage/' . $path,
                    'title' => $image->getClientOriginalName(),
                    'order' => $index + 1,
                    'is_featured' => $index === 0, // Première image = featured
                    'mime_type' => $image->getMimeType(),
                    'size' => $image->getSize(),
                ]);
            }
        }
        
        // Mettre à jour le compteur d'abonnement
        if ($user->current_subscription) {
            $user->current_subscription->increment('properties_used');
        }
        
        return redirect()->route('agent.properties.show', $property)
            ->with('success', 'Propriété créée avec succès !');
    }

    public function show(Property $property)
    {
        // Vérifier que la propriété appartient à l'agent
        if ($property->user_id !== Auth::id()) {
            abort(403);
        }
        
        $property->load(['media', 'details', 'views', 'messages']);
        
        // Statistiques de la propriété
        $stats = [
            'total_views' => $property->views()->count(),
            'this_month_views' => $property->views()->whereMonth('created_at', now()->month)->count(),
            'messages_count' => $property->messages()->count(),
            'unread_messages' => $property->messages()->whereNull('read_at')->count(),
        ];
        
        return view('agent.properties.show', compact('property', 'stats'));
    }

    public function edit(Property $property)
    {
        // Vérifier que la propriété appartient à l'agent
        if ($property->user_id !== Auth::id()) {
            abort(403);
        }
        
        $cities = City::active()->orderBy('name')->get();
        $neighborhoods = Neighborhood::orderBy('name')->get();
        
        return view('agent.properties.edit', compact('property', 'cities', 'neighborhoods'));
    }

    public function update(Request $request, Property $property)
    {
        // Vérifier que la propriété appartient à l'agent
        if ($property->user_id !== Auth::id()) {
            abort(403);
        }
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:house,apartment,land,commercial,hotel',
            'status' => 'required|in:for_sale,for_rent,hotel_room',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|in:XAF,EUR,USD',
            'surface_area' => 'required|numeric|min:1',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'floor' => 'nullable|integer|min:0',
            'total_floors' => 'nullable|integer|min:1',
            'construction_year' => 'nullable|integer|min:1900|max:' . (date('Y') + 5),
            'energy_rating' => 'nullable|string|in:A,B,C,D,E,F,G',
            'furnished' => 'boolean',
            'parking' => 'boolean',
            'garden' => 'boolean',
            'pool' => 'boolean',
            'security' => 'boolean',
            'elevator' => 'boolean',
            'balcony' => 'boolean',
            'air_conditioning' => 'boolean',
            'nearby_amenities' => 'nullable|string',
            'address' => 'required|string|max:255',
            'city' => 'required|string',
            'neighborhood' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'featured' => 'boolean',
            'published' => 'boolean',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:5120',
        ]);
        
        $property->update($validated);
        
        // Upload des nouvelles images
        if ($request->hasFile('images')) {
            $currentImagesCount = $property->media()->where('type', 'image')->count();
            
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('properties', 'public');
                
                $property->media()->create([
                    'type' => 'image',
                    'path' => 'storage/' . $path,
                    'title' => $image->getClientOriginalName(),
                    'order' => $currentImagesCount + $index + 1,
                    'is_featured' => $currentImagesCount === 0 && $index === 0,
                    'mime_type' => $image->getMimeType(),
                    'size' => $image->getSize(),
                ]);
            }
        }
        
        return redirect()->route('agent.properties.show', $property)
            ->with('success', 'Propriété mise à jour avec succès !');
    }

    public function destroy(Property $property)
    {
        // Vérifier que la propriété appartient à l'agent
        if ($property->user_id !== Auth::id()) {
            abort(403);
        }
        
        // Supprimer les images
        foreach ($property->media as $media) {
            if ($media->path) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $media->path));
            }
        }
        
        $property->delete();
        
        return redirect()->route('agent.properties.index')
            ->with('success', 'Propriété supprimée avec succès !');
    }

    public function toggleFeatured(Property $property)
    {
        // Vérifier que la propriété appartient à l'agent
        if ($property->user_id !== Auth::id()) {
            abort(403);
        }
        
        $property->update(['featured' => !$property->featured]);
        
        return response()->json([
            'success' => true,
            'featured' => $property->featured,
            'message' => $property->featured ? 'Propriété mise en vedette' : 'Propriété retirée de la vedette'
        ]);
    }

    public function togglePublished(Property $property)
    {
        // Vérifier que la propriété appartient à l'agent
        if ($property->user_id !== Auth::id()) {
            abort(403);
        }
        
        $property->update(['published' => !$property->published]);
        
        return response()->json([
            'success' => true,
            'published' => $property->published,
            'message' => $property->published ? 'Propriété publiée' : 'Propriété mise en brouillon'
        ]);
    }

    public function duplicate(Property $property)
    {
        // Vérifier que la propriété appartient à l'agent
        if ($property->user_id !== Auth::id()) {
            abort(403);
        }
        
        $user = Auth::user();
        
        // Vérifier si l'agent peut ajouter une propriété
        if (!$user->canPostProperty()) {
            return response()->json([
                'success' => false,
                'message' => 'Vous avez atteint la limite de propriétés de votre abonnement.'
            ], 403);
        }
        
        $newProperty = $property->replicate();
        $newProperty->title = $property->title . ' (Copie)';
        $newProperty->slug = Str::slug($newProperty->title);
        $newProperty->published = false;
        $newProperty->featured = false;
        $newProperty->views_count = 0;
        $newProperty->save();
        
        // Dupliquer les médias
        foreach ($property->media as $media) {
            $newProperty->media()->create($media->toArray());
        }
        
        // Mettre à jour le compteur d'abonnement
        if ($user->current_subscription) {
            $user->current_subscription->increment('properties_used');
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Propriété dupliquée avec succès !',
            'redirect' => route('agent.properties.edit', $newProperty)
        ]);
    }

    public function deleteMedia($mediaId)
    {
        $media = \App\Models\PropertyMedia::findOrFail($mediaId);
        
        // Vérifier que le média appartient à une propriété de l'agent
        if ($media->property->user_id !== Auth::id()) {
            abort(403);
        }
        
        // Supprimer le fichier
        if ($media->path) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $media->path));
        }
        
        // Supprimer l'enregistrement
        $media->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Image supprimée avec succès !'
        ]);
    }
}
