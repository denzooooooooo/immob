<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Models\Property;
use App\Models\City;
use App\Models\SiteSetting;

class CacheService
{
    /**
     * Durées de cache en minutes
     */
    const CACHE_DURATIONS = [
        'properties_featured' => 60,        // 1 heure
        'properties_recent' => 30,          // 30 minutes
        'cities_popular' => 120,            // 2 heures
        'site_settings' => 1440,            // 24 heures
        'statistics' => 60,                 // 1 heure
        'search_results' => 15,             // 15 minutes
        'user_favorites' => 30,             // 30 minutes
        'property_views' => 5,              // 5 minutes
    ];

    /**
     * Préfixes pour les clés de cache
     */
    const CACHE_PREFIXES = [
        'property' => 'prop',
        'user' => 'user',
        'city' => 'city',
        'search' => 'search',
        'stats' => 'stats',
        'settings' => 'settings',
    ];

    /**
     * Obtenir les propriétés en vedette avec cache
     */
    public function getFeaturedProperties(int $limit = 6): \Illuminate\Database\Eloquent\Collection
    {
        $cacheKey = $this->generateCacheKey('properties_featured', ['limit' => $limit]);

        return Cache::remember($cacheKey, self::CACHE_DURATIONS['properties_featured'], function () use ($limit) {
            return Property::where('featured', true)
                ->where('published', true)
                ->with(['media', 'cityModel'])
                ->orderBy('updated_at', 'desc')
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Obtenir les propriétés récentes avec cache
     */
    public function getRecentProperties(int $limit = 12): \Illuminate\Database\Eloquent\Collection
    {
        $cacheKey = $this->generateCacheKey('properties_recent', ['limit' => $limit]);

        return Cache::remember($cacheKey, self::CACHE_DURATIONS['properties_recent'], function () use ($limit) {
            return Property::where('published', true)
                ->with(['media', 'cityModel'])
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Obtenir les villes populaires avec cache
     */
    public function getPopularCities(int $limit = 8): \Illuminate\Database\Eloquent\Collection
    {
        $cacheKey = $this->generateCacheKey('cities_popular', ['limit' => $limit]);

        return Cache::remember($cacheKey, self::CACHE_DURATIONS['cities_popular'], function () use ($limit) {
            return City::whereHas('properties', function ($query) {
                    $query->where('published', true)
                          ->whereNull('deleted_at');
                })
                ->withCount(['properties' => function ($query) {
                    $query->where('published', true)
                          ->whereNull('deleted_at');
                }])
                ->orderBy('properties_count', 'desc')
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Obtenir les paramètres du site avec cache
     */
    public function getSiteSettings(): array
    {
        $cacheKey = $this->generateCacheKey('site_settings');

        return Cache::remember($cacheKey, self::CACHE_DURATIONS['site_settings'], function () {
            return SiteSetting::getAllSettings();
        });
    }

    /**
     * Obtenir les statistiques générales avec cache
     */
    public function getGeneralStatistics(): array
    {
        $cacheKey = $this->generateCacheKey('statistics', ['type' => 'general']);

        return Cache::remember($cacheKey, self::CACHE_DURATIONS['statistics'], function () {
            return [
                'total_properties' => Property::where('published', true)->count(),
                'total_cities' => City::whereHas('properties', function ($query) {
                    $query->where('published', true);
                })->count(),
                'properties_for_sale' => Property::where('status', 'for_sale')->where('published', true)->count(),
                'properties_for_rent' => Property::where('status', 'for_rent')->where('published', true)->count(),
                'properties_hotel' => Property::where('status', 'hotel')->where('published', true)->count(),
            ];
        });
    }

    /**
     * Mettre en cache les résultats de recherche
     */
    public function cacheSearchResults(string $searchHash, $results, int $duration = null): void
    {
        $cacheKey = $this->generateCacheKey('search_results', ['hash' => $searchHash]);
        $duration = $duration ?? self::CACHE_DURATIONS['search_results'];

        Cache::put($cacheKey, $results, $duration);
    }

    /**
     * Obtenir les résultats de recherche en cache
     */
    public function getSearchResults(string $searchHash)
    {
        $cacheKey = $this->generateCacheKey('search_results', ['hash' => $searchHash]);
        return Cache::get($cacheKey);
    }

    /**
     * Mettre en cache les favoris d'un utilisateur
     */
    public function cacheUserFavorites(int $userId, array $favoriteIds): void
    {
        $cacheKey = $this->generateCacheKey('user_favorites', ['user_id' => $userId]);
        Cache::put($cacheKey, $favoriteIds, self::CACHE_DURATIONS['user_favorites']);
    }

    /**
     * Obtenir les favoris d'un utilisateur en cache
     */
    public function getUserFavorites(int $userId): ?array
    {
        $cacheKey = $this->generateCacheKey('user_favorites', ['user_id' => $userId]);
        return Cache::get($cacheKey);
    }

    /**
     * Incrémenter le compteur de vues d'une propriété avec cache
     */
    public function incrementPropertyViews(int $propertyId): void
    {
        $cacheKey = $this->generateCacheKey('property_views', ['property_id' => $propertyId]);
        
        // Incrémenter le compteur en cache
        $views = Cache::get($cacheKey, 0);
        Cache::put($cacheKey, $views + 1, self::CACHE_DURATIONS['property_views']);

        // Mettre à jour la base de données toutes les 10 vues ou après expiration du cache
        if (($views + 1) % 10 === 0) {
            $this->flushPropertyViews($propertyId);
        }
    }

    /**
     * Vider le cache des vues et mettre à jour la base de données
     */
    public function flushPropertyViews(int $propertyId): void
    {
        $cacheKey = $this->generateCacheKey('property_views', ['property_id' => $propertyId]);
        $cachedViews = Cache::get($cacheKey, 0);

        if ($cachedViews > 0) {
            try {
                Property::where('id', $propertyId)->increment('views_count', $cachedViews);
                Cache::forget($cacheKey);
            } catch (\Exception $e) {
                Log::error('Erreur lors de la mise à jour des vues de propriété', [
                    'property_id' => $propertyId,
                    'cached_views' => $cachedViews,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    /**
     * Invalider le cache des propriétés
     */
    public function invalidatePropertiesCache(): void
    {
        $patterns = [
            'properties_featured*',
            'properties_recent*',
            'statistics*',
        ];

        foreach ($patterns as $pattern) {
            $this->forgetByPattern($pattern);
        }
    }

    /**
     * Invalider le cache des villes
     */
    public function invalidateCitiesCache(): void
    {
        $this->forgetByPattern('cities_popular*');
        $this->forgetByPattern('statistics*');
    }

    /**
     * Invalider le cache des paramètres du site
     */
    public function invalidateSiteSettingsCache(): void
    {
        $this->forgetByPattern('site_settings*');
    }

    /**
     * Invalider le cache d'un utilisateur
     */
    public function invalidateUserCache(int $userId): void
    {
        $patterns = [
            "user_favorites*user_id:{$userId}*",
        ];

        foreach ($patterns as $pattern) {
            $this->forgetByPattern($pattern);
        }
    }

    /**
     * Invalider tout le cache de recherche
     */
    public function invalidateSearchCache(): void
    {
        $this->forgetByPattern('search_results*');
    }

    /**
     * Générer une clé de cache
     */
    private function generateCacheKey(string $type, array $params = []): string
    {
        $prefix = self::CACHE_PREFIXES[explode('_', $type)[0]] ?? 'app';
        $key = $prefix . ':' . $type;

        if (!empty($params)) {
            ksort($params);
            $paramString = http_build_query($params);
            $key .= ':' . md5($paramString);
        }

        return $key;
    }

    /**
     * Supprimer les clés de cache par motif
     */
    private function forgetByPattern(string $pattern): void
    {
        try {
            // Pour Redis
            if (Cache::getStore() instanceof \Illuminate\Cache\RedisStore) {
                $redisStore = Cache::getStore();
                if (method_exists($redisStore, 'getRedis')) {
                    $redis = $redisStore->getRedis();
                    if (method_exists($redis, 'keys')) {
                        $keys = $redis->keys($pattern);
                        if (!empty($keys)) {
                            $redis->del($keys);
                        }
                    }
                }
            }
            // Pour les autres stores, on utilise une approche différente
            else {
                // Stocker les clés à supprimer dans un registre
                $registryKey = 'cache_registry:' . str_replace('*', '', $pattern);
                $keys = Cache::get($registryKey, []);
                
                foreach ($keys as $key) {
                    Cache::forget($key);
                }
                
                Cache::forget($registryKey);
            }
        } catch (\Exception $e) {
            Log::warning('Erreur lors de la suppression du cache par motif', [
                'pattern' => $pattern,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Enregistrer une clé dans le registre pour la suppression par motif
     */
    private function registerCacheKey(string $key, string $type): void
    {
        if (Cache::getStore() instanceof \Illuminate\Cache\RedisStore) {
            return; // Redis gère les motifs nativement
        }

        $registryKey = 'cache_registry:' . $type;
        $keys = Cache::get($registryKey, []);
        $keys[] = $key;
        Cache::put($registryKey, array_unique($keys), 1440); // 24 heures
    }

    /**
     * Obtenir les informations sur le cache
     */
    public function getCacheInfo(): array
    {
        try {
            $store = Cache::getStore();
            $info = [
                'driver' => config('cache.default'),
                'store_class' => get_class($store),
            ];

            // Informations spécifiques à Redis
            if ($store instanceof \Illuminate\Cache\RedisStore) {
                if (method_exists($store, 'getRedis')) {
                    $redis = $store->getRedis();
                    if (method_exists($redis, 'info')) {
                        $redisInfo = $redis->info();
                        $info['redis'] = [
                            'version' => $redisInfo['redis_version'] ?? 'unknown',
                            'used_memory' => $redisInfo['used_memory_human'] ?? 'unknown',
                            'connected_clients' => $redisInfo['connected_clients'] ?? 'unknown',
                        ];
                    }
                }
            }

            return $info;
        } catch (\Exception $e) {
            return [
                'error' => 'Impossible d\'obtenir les informations du cache',
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Vider tout le cache de l'application
     */
    public function flushAll(): bool
    {
        try {
            Cache::flush();
            Log::info('Cache de l\'application vidé');
            return true;
        } catch (\Exception $e) {
            Log::error('Erreur lors du vidage du cache', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Préchauffer le cache avec les données essentielles
     */
    public function warmUp(): void
    {
        try {
            Log::info('Début du préchauffage du cache');

            // Préchauffer les données principales
            $this->getFeaturedProperties();
            $this->getRecentProperties();
            $this->getPopularCities();
            $this->getSiteSettings();
            $this->getGeneralStatistics();

            Log::info('Préchauffage du cache terminé');
        } catch (\Exception $e) {
            Log::error('Erreur lors du préchauffage du cache', ['error' => $e->getMessage()]);
        }
    }
}
