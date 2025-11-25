<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Models\Property;
use App\Models\User;
use App\Models\PropertyView;
use Carbon\Carbon;

class AnalyticsService
{
    /**
     * Durées de cache pour les analytics
     */
    const CACHE_DURATIONS = [
        'daily_stats' => 60,        // 1 heure
        'weekly_stats' => 360,      // 6 heures
        'monthly_stats' => 1440,    // 24 heures
        'popular_properties' => 120, // 2 heures
        'user_activity' => 30,      // 30 minutes
    ];

    /**
     * Obtenir les statistiques du tableau de bord
     */
    public function getDashboardStats(): array
    {
        $cacheKey = 'analytics:dashboard_stats';
        
        return Cache::remember($cacheKey, self::CACHE_DURATIONS['daily_stats'], function () {
            $today = Carbon::today();
            $yesterday = Carbon::yesterday();
            $thisWeek = Carbon::now()->startOfWeek();
            $lastWeek = Carbon::now()->subWeek()->startOfWeek();
            $thisMonth = Carbon::now()->startOfMonth();
            $lastMonth = Carbon::now()->subMonth()->startOfMonth();

            return [
                'properties' => [
                    'total' => Property::count(),
                    'published' => Property::where('published', true)->count(),
                    'featured' => Property::where('featured', true)->count(),
                    'today' => Property::whereDate('created_at', $today)->count(),
                    'this_week' => Property::where('created_at', '>=', $thisWeek)->count(),
                    'this_month' => Property::where('created_at', '>=', $thisMonth)->count(),
                ],
                'users' => [
                    'total' => User::count(),
                    'active' => User::where('status', 'active')->count(),
                    'agents' => User::where('role', 'agent')->count(),
                    'clients' => User::where('role', 'client')->count(),
                    'today' => User::whereDate('created_at', $today)->count(),
                    'this_week' => User::where('created_at', '>=', $thisWeek)->count(),
                    'this_month' => User::where('created_at', '>=', $thisMonth)->count(),
                ],
                'views' => [
                    'total' => PropertyView::count(),
                    'today' => PropertyView::whereDate('created_at', $today)->count(),
                    'yesterday' => PropertyView::whereDate('created_at', $yesterday)->count(),
                    'this_week' => PropertyView::where('created_at', '>=', $thisWeek)->count(),
                    'last_week' => PropertyView::whereBetween('created_at', [$lastWeek, $thisWeek])->count(),
                ],
                'revenue' => [
                    // TODO: Implémenter quand le système de paiement sera complet
                    'today' => 0,
                    'this_week' => 0,
                    'this_month' => 0,
                ],
            ];
        });
    }

    /**
     * Obtenir les propriétés les plus populaires
     */
    public function getPopularProperties(int $limit = 10, string $period = '30days'): array
    {
        $cacheKey = "analytics:popular_properties:{$period}:{$limit}";
        
        return Cache::remember($cacheKey, self::CACHE_DURATIONS['popular_properties'], function () use ($limit, $period) {
            $startDate = match ($period) {
                '7days' => Carbon::now()->subDays(7),
                '30days' => Carbon::now()->subDays(30),
                '90days' => Carbon::now()->subDays(90),
                default => Carbon::now()->subDays(30),
            };

            return Property::select('properties.*')
                ->selectRaw('COUNT(property_views.id) as total_views')
                ->leftJoin('property_views', 'properties.id', '=', 'property_views.property_id')
                ->where('properties.published', true)
                ->where('property_views.created_at', '>=', $startDate)
                ->groupBy('properties.id')
                ->orderBy('total_views', 'desc')
                ->limit($limit)
                ->with(['media', 'cityModel'])
                ->get()
                ->toArray();
        });
    }

    /**
     * Obtenir les statistiques de vues par période
     */
    public function getViewsStatistics(string $period = '30days'): array
    {
        $cacheKey = "analytics:views_stats:{$period}";
        
        return Cache::remember($cacheKey, self::CACHE_DURATIONS['daily_stats'], function () use ($period) {
            $days = match ($period) {
                '7days' => 7,
                '30days' => 30,
                '90days' => 90,
                default => 30,
            };

            $startDate = Carbon::now()->subDays($days);
            
            $dailyViews = PropertyView::select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('COUNT(*) as views'),
                    DB::raw('COUNT(DISTINCT property_id) as unique_properties'),
                    DB::raw('COUNT(DISTINCT ip_address) as unique_visitors')
                )
                ->where('created_at', '>=', $startDate)
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            return [
                'daily_views' => $dailyViews,
                'total_views' => $dailyViews->sum('views'),
                'average_daily_views' => round($dailyViews->avg('views'), 2),
                'peak_day' => $dailyViews->sortByDesc('views')->first(),
            ];
        });
    }

    /**
     * Obtenir les statistiques par ville
     */
    public function getCityStatistics(): array
    {
        $cacheKey = 'analytics:city_stats';
        
        return Cache::remember($cacheKey, self::CACHE_DURATIONS['weekly_stats'], function () {
            return Property::select('city')
                ->selectRaw('COUNT(*) as total_properties')
                ->selectRaw('COUNT(CASE WHEN published = 1 THEN 1 END) as published_properties')
                ->selectRaw('COUNT(CASE WHEN featured = 1 THEN 1 END) as featured_properties')
                ->selectRaw('AVG(price) as average_price')
                ->selectRaw('MIN(price) as min_price')
                ->selectRaw('MAX(price) as max_price')
                ->groupBy('city')
                ->orderBy('total_properties', 'desc')
                ->get()
                ->toArray();
        });
    }

    /**
     * Obtenir les statistiques par type de propriété
     */
    public function getPropertyTypeStatistics(): array
    {
        $cacheKey = 'analytics:property_type_stats';
        
        return Cache::remember($cacheKey, self::CACHE_DURATIONS['weekly_stats'], function () {
            return Property::select('type')
                ->selectRaw('COUNT(*) as total')
                ->selectRaw('COUNT(CASE WHEN published = 1 THEN 1 END) as published')
                ->selectRaw('AVG(price) as average_price')
                ->selectRaw('AVG(surface_area) as average_surface')
                ->groupBy('type')
                ->orderBy('total', 'desc')
                ->get()
                ->toArray();
        });
    }

    /**
     * Obtenir les statistiques des utilisateurs actifs
     */
    public function getUserActivityStats(): array
    {
        $cacheKey = 'analytics:user_activity_stats';
        
        return Cache::remember($cacheKey, self::CACHE_DURATIONS['user_activity'], function () {
            $today = Carbon::today();
            $thisWeek = Carbon::now()->startOfWeek();
            $thisMonth = Carbon::now()->startOfMonth();

            return [
                'active_today' => User::whereDate('last_login_at', $today)->count(),
                'active_this_week' => User::where('last_login_at', '>=', $thisWeek)->count(),
                'active_this_month' => User::where('last_login_at', '>=', $thisMonth)->count(),
                'by_role' => User::select('role')
                    ->selectRaw('COUNT(*) as total')
                    ->selectRaw('COUNT(CASE WHEN last_login_at >= ? THEN 1 END) as active_today', [$today])
                    ->selectRaw('COUNT(CASE WHEN last_login_at >= ? THEN 1 END) as active_week', [$thisWeek])
                    ->groupBy('role')
                    ->get()
                    ->toArray(),
            ];
        });
    }

    /**
     * Obtenir les tendances de recherche
     */
    public function getSearchTrends(int $days = 30): array
    {
        $cacheKey = "analytics:search_trends:{$days}";
        
        return Cache::remember($cacheKey, self::CACHE_DURATIONS['daily_stats'], function () use ($days) {
            // TODO: Implémenter le tracking des recherches
            // Pour l'instant, retourner des données simulées
            return [
                'popular_keywords' => [
                    ['keyword' => 'appartement libreville', 'count' => 245],
                    ['keyword' => 'maison port-gentil', 'count' => 189],
                    ['keyword' => 'terrain franceville', 'count' => 156],
                    ['keyword' => 'villa owendo', 'count' => 134],
                    ['keyword' => 'bureau libreville', 'count' => 98],
                ],
                'popular_filters' => [
                    ['filter' => 'type:apartment', 'count' => 567],
                    ['filter' => 'city:libreville', 'count' => 445],
                    ['filter' => 'status:for_rent', 'count' => 389],
                    ['filter' => 'bedrooms:2', 'count' => 234],
                    ['filter' => 'price:100000-500000', 'count' => 198],
                ],
            ];
        });
    }

    /**
     * Enregistrer une vue de propriété
     */
    public function recordPropertyView(int $propertyId, ?int $userId = null, string $ipAddress = null): void
    {
        try {
            // Éviter les doublons dans la même session
            $sessionKey = "property_view:{$propertyId}:" . session()->getId();
            
            if (!Cache::has($sessionKey)) {
                PropertyView::create([
                    'property_id' => $propertyId,
                    'user_id' => $userId,
                    'ip_address' => $ipAddress ?: request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'referer' => request()->header('referer'),
                ]);

                // Marquer comme vu pour cette session (expire après 1 heure)
                Cache::put($sessionKey, true, 60);

                // Incrémenter le compteur de vues de la propriété
                Property::where('id', $propertyId)->increment('views_count');
            }
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'enregistrement de la vue de propriété', [
                'property_id' => $propertyId,
                'user_id' => $userId,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Obtenir les statistiques de performance du site
     */
    public function getPerformanceStats(): array
    {
        $cacheKey = 'analytics:performance_stats';
        
        return Cache::remember($cacheKey, self::CACHE_DURATIONS['daily_stats'], function () {
            return [
                'database' => [
                    'total_queries' => 0, // TODO: Implémenter le comptage des requêtes
                    'slow_queries' => 0,
                    'average_query_time' => 0,
                ],
                'cache' => [
                    'hit_rate' => 0, // TODO: Implémenter les métriques de cache
                    'miss_rate' => 0,
                    'total_keys' => 0,
                ],
                'storage' => [
                    'total_images' => 0, // TODO: Compter les fichiers uploadés
                    'total_size' => 0,
                    'average_image_size' => 0,
                ],
            ];
        });
    }

    /**
     * Générer un rapport d'activité
     */
    public function generateActivityReport(Carbon $startDate, Carbon $endDate): array
    {
        return [
            'period' => [
                'start' => $startDate->toDateString(),
                'end' => $endDate->toDateString(),
                'days' => $startDate->diffInDays($endDate),
            ],
            'properties' => [
                'created' => Property::whereBetween('created_at', [$startDate, $endDate])->count(),
                'published' => Property::whereBetween('created_at', [$startDate, $endDate])
                    ->where('published', true)->count(),
                'views' => PropertyView::whereBetween('created_at', [$startDate, $endDate])->count(),
            ],
            'users' => [
                'registered' => User::whereBetween('created_at', [$startDate, $endDate])->count(),
                'active' => User::whereBetween('last_login_at', [$startDate, $endDate])->count(),
            ],
            'top_properties' => Property::select('properties.*')
                ->selectRaw('COUNT(property_views.id) as views_count')
                ->leftJoin('property_views', 'properties.id', '=', 'property_views.property_id')
                ->whereBetween('property_views.created_at', [$startDate, $endDate])
                ->groupBy('properties.id')
                ->orderBy('views_count', 'desc')
                ->limit(10)
                ->get(),
            'top_cities' => Property::select('city')
                ->selectRaw('COUNT(*) as properties_count')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('city')
                ->orderBy('properties_count', 'desc')
                ->limit(10)
                ->get(),
        ];
    }

    /**
     * Obtenir les métriques en temps réel
     */
    public function getRealTimeMetrics(): array
    {
        return [
            'online_users' => $this->getOnlineUsersCount(),
            'active_sessions' => $this->getActiveSessionsCount(),
            'current_views' => $this->getCurrentViewsCount(),
            'recent_registrations' => User::where('created_at', '>=', Carbon::now()->subHour())->count(),
            'recent_properties' => Property::where('created_at', '>=', Carbon::now()->subHour())->count(),
        ];
    }

    /**
     * Compter les utilisateurs en ligne
     */
    private function getOnlineUsersCount(): int
    {
        // TODO: Implémenter le tracking des utilisateurs en ligne
        return Cache::get('online_users_count', 0);
    }

    /**
     * Compter les sessions actives
     */
    private function getActiveSessionsCount(): int
    {
        try {
            return DB::table('sessions')->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Compter les vues actuelles
     */
    private function getCurrentViewsCount(): int
    {
        return PropertyView::where('created_at', '>=', Carbon::now()->subMinutes(5))->count();
    }

    /**
     * Invalider le cache des analytics
     */
    public function invalidateCache(): void
    {
        $patterns = [
            'analytics:*',
        ];

        foreach ($patterns as $pattern) {
            // TODO: Implémenter la suppression par pattern selon le driver de cache
            Cache::flush(); // Pour l'instant, vider tout le cache
        }

        Log::info('Cache des analytics invalidé');
    }

    /**
     * Exporter les données analytics
     */
    public function exportData(string $format = 'json', Carbon $startDate = null, Carbon $endDate = null): array
    {
        $startDate = $startDate ?: Carbon::now()->subDays(30);
        $endDate = $endDate ?: Carbon::now();

        $data = [
            'export_info' => [
                'generated_at' => Carbon::now()->toISOString(),
                'period' => [
                    'start' => $startDate->toDateString(),
                    'end' => $endDate->toDateString(),
                ],
                'format' => $format,
            ],
            'dashboard_stats' => $this->getDashboardStats(),
            'popular_properties' => $this->getPopularProperties(),
            'views_statistics' => $this->getViewsStatistics(),
            'city_statistics' => $this->getCityStatistics(),
            'property_type_statistics' => $this->getPropertyTypeStatistics(),
            'user_activity' => $this->getUserActivityStats(),
            'activity_report' => $this->generateActivityReport($startDate, $endDate),
        ];

        return $data;
    }

    /**
     * Planifier les tâches de nettoyage des analytics
     */
    public function cleanup(): void
    {
        try {
            // Supprimer les anciennes vues (plus de 1 an)
            $cutoffDate = Carbon::now()->subYear();
            PropertyView::where('created_at', '<', $cutoffDate)->delete();

            Log::info('Nettoyage des analytics effectué', [
                'cutoff_date' => $cutoffDate->toDateString()
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors du nettoyage des analytics', [
                'error' => $e->getMessage()
            ]);
        }
    }
}
