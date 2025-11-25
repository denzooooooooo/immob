<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Neighborhood;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LocationController extends Controller
{
    public function cities(Request $request)
    {
        $query = City::withCount('properties')
            ->withCount('neighborhoods');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('region', 'like', "%{$search}%");
            });
        }

        if ($request->filled('region')) {
            $query->where('region', $request->region);
        }

        $cities = $query->orderBy('name')->paginate(20);
        $regions = City::distinct('region')->pluck('region');

        return view('admin.locations.cities', compact('cities', 'regions'));
    }

    public function storeCity(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'region' => 'required|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        try {
            City::create($validated);

            return redirect()
                ->route('admin.locations.cities.index')
                ->with('success', 'Ville créée avec succès.');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la création de la ville.');
        }
    }

    public function updateCity(Request $request, City $city)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'region' => 'required|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        try {
            $city->update($validated);

            return redirect()
                ->route('admin.locations.cities.index')
                ->with('success', 'Ville mise à jour avec succès.');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la mise à jour de la ville.');
        }
    }

    public function destroyCity(City $city)
    {
        if ($city->properties()->exists()) {
            return back()->with('error', 'Impossible de supprimer une ville contenant des propriétés.');
        }

        try {
            $city->delete();

            return redirect()
                ->route('admin.locations.cities.index')
                ->with('success', 'Ville supprimée avec succès.');

        } catch (\Exception $e) {
            return back()->with('error', 'Une erreur est survenue lors de la suppression de la ville.');
        }
    }

    public function neighborhoods(Request $request)
    {
        $query = Neighborhood::with('city')
            ->withCount('properties');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhereHas('city', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('city')) {
            $query->where('city_id', $request->city);
        }

        $neighborhoods = $query->orderBy('name')->paginate(20);
        $cities = City::orderBy('name')->get();

        return view('admin.locations.neighborhoods', compact('neighborhoods', 'cities'));
    }

    public function storeNeighborhood(Request $request)
    {
        $validated = $request->validate([
            'city_id' => 'required|exists:cities,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        try {
            Neighborhood::create($validated);

            return redirect()
                ->route('admin.locations.neighborhoods.index')
                ->with('success', 'Quartier créé avec succès.');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la création du quartier.');
        }
    }

    public function updateNeighborhood(Request $request, Neighborhood $neighborhood)
    {
        $validated = $request->validate([
            'city_id' => 'required|exists:cities,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        try {
            $neighborhood->update($validated);

            return redirect()
                ->route('admin.locations.neighborhoods.index')
                ->with('success', 'Quartier mis à jour avec succès.');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la mise à jour du quartier.');
        }
    }

    public function destroyNeighborhood(Neighborhood $neighborhood)
    {
        if ($neighborhood->properties()->exists()) {
            return back()->with('error', 'Impossible de supprimer un quartier contenant des propriétés.');
        }

        try {
            $neighborhood->delete();

            return redirect()
                ->route('admin.locations.neighborhoods.index')
                ->with('success', 'Quartier supprimé avec succès.');

        } catch (\Exception $e) {
            return back()->with('error', 'Une erreur est survenue lors de la suppression du quartier.');
        }
    }

    /**
     * Get neighborhoods by city slug for AJAX requests
     */
    public function getNeighborhoodsByCity($citySlug)
    {
        try {
            // Find the city by slug
            $city = City::where('slug', $citySlug)->first();

            if (!$city) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ville non trouvée'
                ], 404);
            }

            // Get neighborhoods for this city
            $neighborhoods = Neighborhood::where('city_id', $city->id)
                ->where('is_active', true)
                ->select('slug', 'name')
                ->orderBy('name')
                ->get();

            return response()->json($neighborhoods);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des quartiers'
            ], 500);
        }
    }
}
