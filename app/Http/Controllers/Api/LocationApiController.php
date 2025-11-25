<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Neighborhood;
use Illuminate\Http\Request;

class LocationApiController extends Controller
{
    /**
     * Get neighborhoods by city slug
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

    /**
     * Get all cities
     */
    public function getCities()
    {
        try {
            $cities = City::where('is_active', true)
                ->select('slug', 'name')
                ->orderBy('name')
                ->get();

            return response()->json($cities);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des villes'
            ], 500);
        }
    }
}
