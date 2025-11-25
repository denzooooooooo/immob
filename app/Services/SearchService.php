<?php

namespace App\Services;

use App\Models\Property;
use App\Models\City;
use App\Models\Neighborhood;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class SearchService
{
    private $cacheService;

    public function __construct(CacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    /**
     * Recherche avancée de propriétés
     */
    public function searchProperties($params = [])
    {
        $cacheKey = 'search:' . md5(serialize($params));
        
        return Cache::remember($cacheKey, 900, function () use ($params) {
            $query = Property::with(['media', 'city', 'neighborhood', 'agent'])
                ->where('status', 'active');

            // Recherche textuelle
            if (!empty($params['q'])) {
                $searchTerm = $params['q'];
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('title', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('description', 'LIKE', "%{$searchTerm}%")
                      ->orWhereHas('city', function ($cityQuery) use ($searchTerm) {
                          $cityQuery->where('name', 'LIKE', "%{$searchTerm}%");
                      })
                      ->orWhereHas('neighborhood', function ($neighborhoodQuery) use ($searchTerm) {
                          $neighborhoodQuery->where('name', 'LIKE', "%{$searchTerm}%");
                      });
                });
            }

            // Filtres par type
            if (!empty($params['type'])) {
                $query->where('type', $params['type']);
            }

            // Filtres par ville
            if (!empty($params['city_id'])) {
                $query->where('city_id', $params['city_id']);
            }

            // Filtres par quartier
            if (!empty($params['neighborhood_id'])) {
                $query->where('neighborhood_id', $params['neighborhood_id']);
            }

            // Filtres de prix
            if (!empty($params['min_price'])) {
                $query->where('price', '>=', $params['min_price']);
            }
            if (!empty($params['max_price'])) {
                $query->where('price', '<=', $params['max_price']);
            }

            // Filtres de surface
            if (!empty($params['min_area'])) {
                $query->whereHas('details', function ($detailsQuery) use ($params) {
                    $detailsQuery->where('area', '>=', $params['min_area']);
                });
            }
            if (!empty($params['max_area'])) {
                $query->whereHas('details', function ($detailsQuery) use ($params) {
                    $detailsQuery->where('area', '<=', $params['max_area']);
                });
            }

            // Filtres par nombre de chambres
            if (!empty($params['bedrooms'])) {
                $query->whereHas('details', function ($detailsQuery) use ($params) {
                    $detailsQuery->where('bedrooms', '>=', $params['bedrooms']);
                });
            }

            // Filtres par nombre de salles de bain
            if (!empty($params['bathrooms'])) {
                $query->whereHas('details', function ($detailsQuery) use ($params) {
                    $detailsQuery->where('bathrooms', '>=', $params['bathrooms']);
                });
            }

            // Filtres par équipements
            if (!empty($params['amenities'])) {
                $amenities = is_array($params['amenities']) ? $params['amenities'] : [$params['amenities']];
                $query->whereHas('details', function ($detailsQuery) use ($amenities) {
                    foreach ($amenities as $amenity) {
                        $detailsQuery->whereJsonContains('amenities', $amenity);
                    }
                });
            }

            // Tri
            $sortBy = $params['sort_by'] ?? 'created_at';
            $sortOrder = $params['sort_order'] ?? 'desc';

            switch ($sortBy) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'area_asc':
                    $query->join('property_details', 'properties.id', '=', 'property_details.property_id')
                          ->orderBy('property_details.area', 'asc');
                    break;
                case 'area_desc':
                    $query->join('property_details', 'properties.id', '=', 'property_details.property_id')
                          ->orderBy('property_details.area', 'desc');
                    break;
                case 'relevance':
                    $query->orderBy('featured', 'desc')
                          ->orderBy('views_count', 'desc')
                          ->orderBy('created_at', 'desc');
                    break;
                default:
                    $query->orderBy('featured', 'desc')
                          ->orderBy($sortBy, $sortOrder);
            }

            return $query->paginate(12);
        });
    }

    /**
     * Recherche avec suggestions automatiques
     */
    public function getSearchSuggestions($term, $limit = 10)
    {
        $cacheKey = 'suggestions:' . md5($term);
        
        return Cache::remember($cacheKey, 3600, function () use ($term, $limit) {
            $suggestions = [];

            // Suggestions de villes
            $cities = City::where('name', 'LIKE', "%{$term}%")
                ->limit($limit / 2)
                ->get()
                ->map(function ($city) {
                    return [
                        'type' => 'city',
                        'text' => $city->name,
                        'value' => $city->id,
                        'category' => 'Villes'
                    ];
                });

            // Suggestions de quartiers
            $neighborhoods = Neighborhood::where('name', 'LIKE', "%{$term}%")
                ->with('city')
                ->limit($limit / 2)
                ->get()
                ->map(function ($neighborhood) {
                    return [
                        'type' => 'neighborhood',
                        'text' => $neighborhood->name . ', ' . $neighborhood->city->name,
                        'value' => $neighborhood->id,
                        'category' => 'Quartiers'
                    ];
                });

            // Suggestions de types de propriétés
            $types = Property::select('type')
                ->where('type', 'LIKE', "%{$term}%")
                ->distinct()
                ->limit(5)
                ->get()
                ->map(function ($property) {
                    return [
                        'type' => 'property_type',
                        'text' => ucfirst($property->type),
                        'value' => $property->type,
                        'category' => 'Types de propriétés'
                    ];
                });

            return $cities->concat($neighborhoods)->concat($types)->take($limit);
        });
    }

    /**
     * Recherche géographique par rayon
     */
    public function searchByRadius($latitude, $longitude, $radius = 5, $filters = [])
    {
        $query = Property::select('properties.*')
            ->selectRaw(
                '(6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance',
                [$latitude, $longitude, $latitude]
            )
            ->where('status', 'active')
            ->having('distance', '<', $radius)
            ->orderBy('distance');

        // Appliquer les filtres additionnels
        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (!empty($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }

        if (!empty($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }

        return $query->with(['media', 'city', 'neighborhood', 'agent'])->paginate(12);
    }

    /**
     * Recherche de propriétés similaires
     */
    public function findSimilarProperties($propertyId, $limit = 6)
    {
        $property = Property::findOrFail($propertyId);
        
        $cacheKey = 'similar:' . $propertyId;
        
        return Cache::remember($cacheKey, 1800, function () use ($property, $limit) {
            return Property::where('id', '!=', $property->id)
                ->where('status', 'active')
                ->where(function ($query) use ($property) {
                    $query->where('type', $property->type)
                          ->orWhere('city_id', $property->city_id)
                          ->orWhereBetween('price', [
                              $property->price * 0.8,
                              $property->price * 1.2
                          ]);
                })
                ->with(['media', 'city', 'neighborhood'])
                ->orderByRaw('
                    CASE 
                        WHEN type = ? THEN 3
                        WHEN city_id = ? THEN 2
                        WHEN price BETWEEN ? AND ? THEN 1
                        ELSE 0
                    END DESC
                ', [
                    $property->type,
                    $property->city_id,
                    $property->price * 0.8,
                    $property->price * 1.2
                ])
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Recherche avancée avec filtres multiples
     */
    public function advancedSearch($filters)
    {
        $query = Property::query()->where('status', 'active');

        // Recherche textuelle avancée
        if (!empty($filters['search'])) {
            $searchTerms = explode(' ', $filters['search']);
            $query->where(function ($q) use ($searchTerms) {
                foreach ($searchTerms as $term) {
                    $q->where(function ($subQuery) use ($term) {
                        $subQuery->where('title', 'LIKE', "%{$term}%")
                                 ->orWhere('description', 'LIKE', "%{$term}%");
                    });
                }
            });
        }

        // Filtres de localisation
        if (!empty($filters['locations'])) {
            $query->where(function ($q) use ($filters) {
                $q->whereIn('city_id', $filters['locations'])
                  ->orWhereIn('neighborhood_id', $filters['locations']);
            });
        }

        // Filtres de prix avec ranges prédéfinis
        if (!empty($filters['price_range'])) {
            $ranges = $this->getPriceRanges();
            if (isset($ranges[$filters['price_range']])) {
                $range = $ranges[$filters['price_range']];
                $query->whereBetween('price', [$range['min'], $range['max']]);
            }
        }

        // Filtres de caractéristiques
        if (!empty($filters['features'])) {
            $query->whereHas('details', function ($detailsQuery) use ($filters) {
                if (in_array('parking', $filters['features'])) {
                    $detailsQuery->where('parking_spaces', '>', 0);
                }
                if (in_array('garden', $filters['features'])) {
                    $detailsQuery->whereJsonContains('amenities', 'garden');
                }
                if (in_array('pool', $filters['features'])) {
                    $detailsQuery->whereJsonContains('amenities', 'pool');
                }
                if (in_array('security', $filters['features'])) {
                    $detailsQuery->whereJsonContains('amenities', 'security');
                }
            });
        }

        return $query->with(['media', 'city', 'neighborhood', 'agent'])
                    ->orderBy('featured', 'desc')
                    ->orderBy('created_at', 'desc')
                    ->paginate(12);
    }

    /**
     * Obtenir les ranges de prix prédéfinis
     */
    private function getPriceRanges()
    {
        return [
            'budget' => ['min' => 0, 'max' => 50000000],
            'medium' => ['min' => 50000000, 'max' => 150000000],
            'premium' => ['min' => 150000000, 'max' => 300000000],
            'luxury' => ['min' => 300000000, 'max' => PHP_INT_MAX]
        ];
    }

    /**
     * Obtenir les statistiques de recherche
     */
    public function getSearchStats()
    {
        return Cache::remember('search_stats', 3600, function () {
            return [
                'total_properties' => Property::where('status', 'active')->count(),
                'by_type' => Property::where('status', 'active')
                    ->select('type', DB::raw('count(*) as count'))
                    ->groupBy('type')
                    ->get()
                    ->pluck('count', 'type'),
                'by_city' => Property::where('status', 'active')
                    ->join('cities', 'properties.city_id', '=', 'cities.id')
                    ->select('cities.name', DB::raw('count(*) as count'))
                    ->groupBy('cities.name')
                    ->orderBy('count', 'desc')
                    ->limit(10)
                    ->get()
                    ->pluck('count', 'name'),
                'price_ranges' => [
                    'budget' => Property::where('status', 'active')->where('price', '<', 50000000)->count(),
                    'medium' => Property::where('status', 'active')->whereBetween('price', [50000000, 150000000])->count(),
                    'premium' => Property::where('status', 'active')->whereBetween('price', [150000000, 300000000])->count(),
                    'luxury' => Property::where('status', 'active')->where('price', '>', 300000000)->count(),
                ]
            ];
        });
    }

    /**
     * Enregistrer une recherche pour les analytics
     */
    public function logSearch($term, $filters = [], $results_count = 0)
    {
        // Ici on pourrait enregistrer en base pour les analytics
        // Pour l'instant, on utilise le cache pour les recherches populaires
        $popularSearches = Cache::get('popular_searches', []);
        
        if (isset($popularSearches[$term])) {
            $popularSearches[$term]++;
        } else {
            $popularSearches[$term] = 1;
        }
        
        // Garder seulement les 50 recherches les plus populaires
        arsort($popularSearches);
        $popularSearches = array_slice($popularSearches, 0, 50, true);
        
        Cache::put('popular_searches', $popularSearches, 86400); // 24h
    }

    /**
     * Obtenir les recherches populaires
     */
    public function getPopularSearches($limit = 10)
    {
        $popularSearches = Cache::get('popular_searches', []);
        return array_slice($popularSearches, 0, $limit, true);
    }
}
