<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\PropertyView;
use App\Services\SearchService;
use App\Services\SecurityService;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PropertyApiController extends Controller
{
    protected $searchService;
    protected $securityService;
    protected $imageService;

    public function __construct(
        SearchService $searchService,
        SecurityService $securityService,
        ImageService $imageService
    ) {
        $this->searchService = $searchService;
        $this->securityService = $securityService;
        $this->imageService = $imageService;
    }

    /**
     * Liste des propriétés avec filtres et pagination
     */
    public function index(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'page' => 'integer|min:1',
                'per_page' => 'integer|min:1|max:50',
                'sort_by' => 'string|in:price,created_at,views',
                'sort_order' => 'string|in:asc,desc',
                'type' => 'string|in:house,apartment,land,commercial',
                'city_id' => 'integer|exists:cities,id',
                'min_price' => 'numeric|min:0',
                'max_price' => 'numeric|gt:min_price',
                'min_area' => 'numeric|min:0',
                'max_area' => 'numeric|gt:min_area',
                'bedrooms' => 'integer|min:0',
                'bathrooms' => 'integer|min:0',
                'status' => 'string|in:available,sold,rented'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Construire la requête
            $query = Property::with(['details', 'cityModel', 'agent'])
                ->where('status', 'active');

            // Appliquer les filtres
            if ($request->type) {
                $query->where('type', $request->type);
            }
            if ($request->city_id) {
                $query->where('city_id', $request->city_id);
            }
            if ($request->min_price) {
                $query->where('price', '>=', $request->min_price);
            }
            if ($request->max_price) {
                $query->where('price', '<=', $request->max_price);
            }
            if ($request->min_area) {
                $query->whereHas('details', function ($q) use ($request) {
                    $q->where('area', '>=', $request->min_area);
                });
            }
            if ($request->max_area) {
                $query->whereHas('details', function ($q) use ($request) {
                    $q->where('area', '<=', $request->max_area);
                });
            }
            if ($request->bedrooms) {
                $query->whereHas('details', function ($q) use ($request) {
                    $q->where('bedrooms', $request->bedrooms);
                });
            }
            if ($request->bathrooms) {
                $query->whereHas('details', function ($q) use ($request) {
                    $q->where('bathrooms', $request->bathrooms);
                });
            }
            if ($request->status) {
                $query->where('availability_status', $request->status);
            }

            // Tri
            $sortBy = $request->sort_by ?? 'created_at';
            $sortOrder = $request->sort_order ?? 'desc';
            
            if ($sortBy === 'views') {
                $query->withCount('views')->orderBy('views_count', $sortOrder);
            } else {
                $query->orderBy($sortBy, $sortOrder);
            }

            // Pagination
            $perPage = $request->per_page ?? 15;
            $properties = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => [
                    'properties' => $properties,
                    'filters' => [
                        'type' => $request->type,
                        'city_id' => $request->city_id,
                        'price_range' => [
                            'min' => $request->min_price,
                            'max' => $request->max_price
                        ],
                        'area_range' => [
                            'min' => $request->min_area,
                            'max' => $request->max_area
                        ],
                        'bedrooms' => $request->bedrooms,
                        'bathrooms' => $request->bathrooms,
                        'status' => $request->status
                    ],
                    'sort' => [
                        'by' => $sortBy,
                        'order' => $sortOrder
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch properties',
                'error' => config('app.debug') ? $e->getMessage() : 'Server error'
            ], 500);
        }
    }

    /**
     * Détails d'une propriété
     */
    public function show(Request $request, $id)
    {
        try {
            $property = Property::with([
                'details',
                'media',
                'cityModel',
                'agent',
                'agent.properties' => function ($query) {
                    $query->where('status', 'active')
                        ->limit(3);
                }
            ])->findOrFail($id);

            // Vérifier si la propriété est active
            if ($property->status !== 'active') {
                return response()->json([
                    'success' => false,
                    'message' => 'Property not available',
                    'error' => 'property_inactive'
                ], 404);
            }

            // Enregistrer la vue
            if ($request->user()) {
                PropertyView::create([
                    'property_id' => $property->id,
                    'user_id' => $request->user()->id,
                    'ip_address' => $request->ip()
                ]);
            }

            // Incrémenter le compteur de vues
            $property->increment('views_count');

            // Obtenir les propriétés similaires
            $similarProperties = $this->getSimilarProperties($property);

            return response()->json([
                'success' => true,
                'data' => [
                    'property' => $property,
                    'similar_properties' => $similarProperties,
                    'statistics' => [
                        'views' => $property->views_count,
                        'favorites' => $property->favorites_count,
                        'days_listed' => $property->created_at->diffInDays(now())
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch property details',
                'error' => config('app.debug') ? $e->getMessage() : 'Server error'
            ], 500);
        }
    }

    /**
     * Recherche de propriétés
     */
    public function search(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'query' => 'required|string|min:2',
                'filters' => 'array',
                'page' => 'integer|min:1',
                'per_page' => 'integer|min:1|max:50'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Nettoyer la requête
            $query = $this->securityService->sanitizeInput($request->query);

            // Effectuer la recherche
            $results = $this->searchService->searchProperties(
                $query,
                $request->filters ?? [],
                $request->page ?? 1,
                $request->per_page ?? 15
            );

            return response()->json([
                'success' => true,
                'data' => $results
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Search failed',
                'error' => config('app.debug') ? $e->getMessage() : 'Server error'
            ], 500);
        }
    }

    /**
     * Suggestions de recherche
     */
    public function suggestions(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'query' => 'required|string|min:2',
                'limit' => 'integer|min:1|max:10'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $query = $this->securityService->sanitizeInput($request->query);
            $limit = $request->limit ?? 5;

            // Obtenir les suggestions depuis le cache ou la base de données
            $cacheKey = "search_suggestions:{$query}";
            $suggestions = Cache::remember($cacheKey, 3600, function () use ($query, $limit) {
                return $this->searchService->getSearchSuggestions($query, $limit);
            });

            return response()->json([
                'success' => true,
                'data' => [
                    'suggestions' => $suggestions
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get suggestions',
                'error' => config('app.debug') ? $e->getMessage() : 'Server error'
            ], 500);
        }
    }

    /**
     * Propriétés en vedette
     */
    public function featured(Request $request)
    {
        try {
            $limit = min($request->limit ?? 6, 12);

            $properties = Property::with(['details', 'cityModel', 'agent'])
                ->where('status', 'active')
                ->where('is_featured', true)
                ->orderBy('featured_until', 'desc')
                ->limit($limit)
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'properties' => $properties
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch featured properties',
                'error' => config('app.debug') ? $e->getMessage() : 'Server error'
            ], 500);
        }
    }

    /**
     * Propriétés récentes
     */
    public function recent(Request $request)
    {
        try {
            $limit = min($request->limit ?? 8, 20);

            $properties = Property::with(['details', 'cityModel', 'agent'])
                ->where('status', 'active')
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'properties' => $properties
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch recent properties',
                'error' => config('app.debug') ? $e->getMessage() : 'Server error'
            ], 500);
        }
    }

    /**
     * Statistiques des propriétés
     */
    public function stats()
    {
        try {
            $stats = Cache::remember('property_stats', 3600, function () {
                return [
                    'total' => Property::where('status', 'active')->count(),
                    'by_type' => Property::where('status', 'active')
                        ->select('type', DB::raw('count(*) as count'))
                        ->groupBy('type')
                        ->get()
                        ->pluck('count', 'type'),
                    'by_city' => Property::where('status', 'active')
                        ->select('cities.name', DB::raw('count(*) as count'))
                        ->join('cities', 'properties.city_id', '=', 'cities.id')
                        ->groupBy('cities.name')
                        ->orderBy('count', 'desc')
                        ->limit(10)
                        ->get()
                        ->pluck('count', 'name'),
                    'price_ranges' => [
                        'under_50m' => Property::where('status', 'active')
                            ->where('price', '<', 50000000)->count(),
                        '50m_150m' => Property::where('status', 'active')
                            ->whereBetween('price', [50000000, 150000000])->count(),
                        '150m_300m' => Property::where('status', 'active')
                            ->whereBetween('price', [150000000, 300000000])->count(),
                        'over_300m' => Property::where('status', 'active')
                            ->where('price', '>', 300000000)->count()
                    ],
                    'recent_activity' => [
                        'new_listings' => Property::where('status', 'active')
                            ->where('created_at', '>=', now()->subDays(7))
                            ->count(),
                        'views' => PropertyView::where('created_at', '>=', now()->subDays(7))
                            ->count(),
                        'average_price' => Property::where('status', 'active')
                            ->avg('price')
                    ]
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch property statistics',
                'error' => config('app.debug') ? $e->getMessage() : 'Server error'
            ], 500);
        }
    }

    /**
     * Recherche par rayon géographique
     */
    public function searchByRadius(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'latitude' => 'required|numeric|between:-90,90',
                'longitude' => 'required|numeric|between:-180,180',
                'radius' => 'required|numeric|min:0.1|max:50', // en kilomètres
                'filters' => 'array'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $results = $this->searchService->searchByRadius(
                $request->latitude,
                $request->longitude,
                $request->radius,
                $request->filters ?? []
            );

            return response()->json([
                'success' => true,
                'data' => $results
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to perform radius search',
                'error' => config('app.debug') ? $e->getMessage() : 'Server error'
            ], 500);
        }
    }

    /**
     * Obtenir les propriétés similaires
     */
    private function getSimilarProperties(Property $property)
    {
        return Property::with(['details', 'cityModel'])
            ->where('status', 'active')
            ->where('id', '!=', $property->id)
            ->where(function ($query) use ($property) {
                $query->where('type', $property->type)
                    ->orWhere('city_id', $property->city_id);
            })
            ->whereBetween('price', [
                $property->price * 0.7,
                $property->price * 1.3
            ])
            ->limit(3)
            ->get();
    }
}
