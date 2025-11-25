<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Property;
use App\Models\Neighborhood;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        // Récupérer les villes et quartiers pour les filtres
        $cities = City::pluck('name', 'slug');
        $neighborhoods = Neighborhood::pluck('name', 'slug');

        // Construire la requête de base
        $query = Property::query()->published();

        // Appliquer les filtres
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        if ($request->filled('neighborhood')) {
            $query->where('neighborhood', $request->neighborhood);
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->filled('min_surface')) {
            $query->where('surface_area', '>=', $request->min_surface);
        }

        if ($request->filled('max_surface')) {
            $query->where('surface_area', '<=', $request->max_surface);
        }

        if ($request->filled('min_bedrooms')) {
            $query->where('bedrooms', '>=', $request->min_bedrooms);
        }

        if ($request->filled('min_bathrooms')) {
            $query->where('bathrooms', '>=', $request->min_bathrooms);
        }

        // Filtrer par caractéristiques
        if ($request->filled('features')) {
            $query->withFeatures($request->features);
        }

        // Trier les résultats
        $query->when($request->sort, function ($q) use ($request) {
            switch ($request->sort) {
                case 'price_asc':
                    return $q->orderBy('price', 'asc');
                case 'price_desc':
                    return $q->orderBy('price', 'desc');
                case 'date_desc':
                    return $q->orderBy('created_at', 'desc');
                case 'surface_desc':
                    return $q->orderBy('surface_area', 'desc');
                default:
                    return $q->orderBy('created_at', 'desc');
            }
        }, function ($q) {
            return $q->orderBy('created_at', 'desc');
        });

        // Récupérer les résultats paginés
        $properties = $query->with(['featuredImage', 'cityModel', 'neighborhoodModel'])
            ->paginate(12)
            ->withQueryString();

        return view('search.index', compact('properties', 'cities', 'neighborhoods'));
    }
}
