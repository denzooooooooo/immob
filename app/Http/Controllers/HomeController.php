<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\City;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Récupérer quelques propriétés en vedette
        $featuredProperties = Property::where('featured', true)
            ->where('published', true)
            ->with(['media', 'cityModel'])
            ->limit(6)
            ->get();

        // Récupérer les villes principales avec le bon comptage des propriétés publiées
        $cities = City::whereHas('properties', function ($query) {
                $query->where('published', true)
                      ->whereNull('deleted_at');
            })
            ->withCount(['properties' => function ($query) {
                $query->where('published', true)
                      ->whereNull('deleted_at');
            }])
            ->orderBy('properties_count', 'desc')
            ->limit(8)
            ->get();

        // Statistiques générales
        $stats = [
            'total_properties' => Property::where('published', true)->count(),
            'total_cities' => City::whereHas('properties', function ($query) {
                $query->where('published', true);
            })->count(),
            'properties_for_sale' => Property::where('status', 'for_sale')->where('published', true)->count(),
            'properties_for_rent' => Property::where('status', 'for_rent')->where('published', true)->count(),
        ];

        // Récupérer les paramètres du site
        $siteSettings = SiteSetting::getAllSettings();

        return view('home', compact('featuredProperties', 'cities', 'stats', 'siteSettings'));
    }
}
