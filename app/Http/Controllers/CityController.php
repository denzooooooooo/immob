<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Property;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function index()
    {
        $cities = City::withCount('properties')
            ->orderBy('properties_count', 'desc')
            ->get();

        // Statistiques par ville
        $cityStats = $cities->map(function($city) {
            return [
                'name' => $city->name,
                'slug' => $city->slug,
                'total_properties' => $city->properties_count,
                'for_sale' => Property::where('city', $city->name)
                    ->where('status', 'for_sale')
                    ->where('published', true)
                    ->whereNull('deleted_at')
                    ->count(),
                'for_rent' => Property::where('city', $city->name)
                    ->where('status', 'for_rent')
                    ->where('published', true)
                    ->whereNull('deleted_at')
                    ->count(),
                'average_price' => Property::where('city', $city->name)
                    ->where('published', true)
                    ->whereNull('deleted_at')
                    ->avg('price'),
            ];
        });

        return view('cities.index', compact('cityStats'));
    }

    public function show(City $city)
    {
        $properties = Property::where('city', $city->name)
            ->where('published', true)
            ->whereNull('deleted_at')
            ->with(['media', 'cityModel'])
            ->orderBy('featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        // Statistiques de la ville
        $stats = [
            'total_properties' => $city->properties()->where('published', true)->whereNull('deleted_at')->count(),
            'for_sale' => $city->properties()->where('status', 'for_sale')->where('published', true)->whereNull('deleted_at')->count(),
            'for_rent' => $city->properties()->where('status', 'for_rent')->where('published', true)->whereNull('deleted_at')->count(),
            'average_price' => $city->properties()->where('published', true)->whereNull('deleted_at')->avg('price'),
            'min_price' => $city->properties()->where('published', true)->whereNull('deleted_at')->min('price'),
            'max_price' => $city->properties()->where('published', true)->whereNull('deleted_at')->max('price'),
        ];

        // Quartiers populaires
        $neighborhoods = $city->neighborhoods()
            ->withCount(['properties' => function($query) {
                $query->where('published', true);
            }])
            ->orderBy('properties_count', 'desc')
            ->limit(8)
            ->get();

        return view('cities.show', compact('city', 'properties', 'stats', 'neighborhoods'));
    }
}
