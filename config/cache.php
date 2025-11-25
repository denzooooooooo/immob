<?php

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Cache Store
    |--------------------------------------------------------------------------
    |
    | This option controls the default cache connection that gets used while
    | using this caching library. This connection is used when another is
    | not explicitly specified when executing a given caching function.
    |
    */

    'default' => env('CACHE_DRIVER', 'database'),

    /*
    |--------------------------------------------------------------------------
    | Cache Stores
    |--------------------------------------------------------------------------
    |
    | Here you may define all of the cache "stores" for your application as
    | well as their drivers. You may even define multiple stores for the
    | same cache driver to group types of items stored in your caches.
    |
    | Supported drivers: "apc", "array", "database", "file",
    |            "memcached", "redis", "dynamodb", "octane", "null"
    |
    */

    'stores' => [

        'apc' => [
            'driver' => 'apc',
        ],

        'array' => [
            'driver' => 'array',
            'serialize' => false,
        ],

        'database' => [
            'driver' => 'database',
            'table' => 'cache',
            'connection' => null,
            'lock_connection' => null,
        ],

        'file' => [
            'driver' => 'file',
            'path' => storage_path('framework/cache/data'),
            'lock_path' => storage_path('framework/cache/data'),
        ],

        'memcached' => [
            'driver' => 'memcached',
            'persistent_id' => env('MEMCACHED_PERSISTENT_ID'),
            'sasl' => [
                env('MEMCACHED_USERNAME'),
                env('MEMCACHED_PASSWORD'),
            ],
            'options' => [
                // Memcached::OPT_CONNECT_TIMEOUT => 2000,
            ],
            'servers' => [
                [
                    'host' => env('MEMCACHED_HOST', '127.0.0.1'),
                    'port' => env('MEMCACHED_PORT', 11211),
                    'weight' => 100,
                ],
            ],
        ],

        'redis' => [
            'driver' => 'redis',
            'connection' => 'cache',
            'lock_connection' => 'default',
        ],

        'dynamodb' => [
            'driver' => 'dynamodb',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
            'table' => env('DYNAMODB_CACHE_TABLE', 'cache'),
            'endpoint' => env('DYNAMODB_ENDPOINT'),
        ],

        'octane' => [
            'driver' => 'octane',
        ],

        /*
        |--------------------------------------------------------------------------
        | Custom Cache Stores for Monnkama
        |--------------------------------------------------------------------------
        |
        | Custom cache configurations optimized for different types of data
        |
        */

        // Cache pour les propriétés (données fréquemment consultées)
        'properties' => [
            'driver' => env('CACHE_DRIVER', 'database'),
            'table' => 'cache_properties',
            'connection' => null,
            'prefix' => 'prop',
            'serializer' => 'json',
        ],

        // Cache pour les sessions utilisateur
        'sessions' => [
            'driver' => env('SESSION_CACHE_DRIVER', env('CACHE_DRIVER', 'database')),
            'table' => 'cache_sessions',
            'connection' => null,
            'prefix' => 'sess',
        ],

        // Cache pour les recherches
        'searches' => [
            'driver' => env('SEARCH_CACHE_DRIVER', env('CACHE_DRIVER', 'database')),
            'table' => 'cache_searches',
            'connection' => null,
            'prefix' => 'search',
            'ttl' => 900, // 15 minutes par défaut
        ],

        // Cache pour les analytics
        'analytics' => [
            'driver' => env('ANALYTICS_CACHE_DRIVER', env('CACHE_DRIVER', 'database')),
            'table' => 'cache_analytics',
            'connection' => null,
            'prefix' => 'analytics',
            'ttl' => 3600, // 1 heure par défaut
        ],

        // Cache pour les images et médias
        'media' => [
            'driver' => 'file',
            'path' => storage_path('framework/cache/media'),
            'prefix' => 'media',
            'ttl' => 86400, // 24 heures
        ],

        // Cache pour les configurations du site
        'config' => [
            'driver' => env('CONFIG_CACHE_DRIVER', env('CACHE_DRIVER', 'database')),
            'table' => 'cache_config',
            'connection' => null,
            'prefix' => 'config',
            'ttl' => 86400, // 24 heures
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Key Prefix
    |--------------------------------------------------------------------------
    |
    | When utilizing the APC, database, memcached, Redis, or DynamoDB cache
    | stores, there might be other applications using the same cache. For
    | that reason, you may prefix every cache key to avoid collisions.
    |
    */

    'prefix' => env('CACHE_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_').'_cache_'),

    /*
    |--------------------------------------------------------------------------
    | Cache Tags
    |--------------------------------------------------------------------------
    |
    | Cache tags allow you to tag related cache items and then flush them
    | all at once. This is useful for invalidating related cache entries
    | when certain events occur in your application.
    |
    */

    'tags' => [
        'properties' => [
            'featured',
            'recent',
            'popular',
            'by_city',
            'by_type',
        ],
        'users' => [
            'active',
            'agents',
            'clients',
            'statistics',
        ],
        'analytics' => [
            'daily',
            'weekly',
            'monthly',
            'real_time',
        ],
        'searches' => [
            'results',
            'filters',
            'suggestions',
        ],
        'settings' => [
            'site',
            'payment',
            'notification',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Optimization Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for cache optimization and performance tuning
    |
    */

    'optimization' => [
        // Compression des données en cache
        'compression' => [
            'enabled' => env('CACHE_COMPRESSION', true),
            'threshold' => env('CACHE_COMPRESSION_THRESHOLD', 1024), // Compresser si > 1KB
            'level' => env('CACHE_COMPRESSION_LEVEL', 6), // Niveau de compression (1-9)
        ],

        // Sérialisation
        'serialization' => [
            'method' => env('CACHE_SERIALIZATION', 'serialize'), // serialize, json, igbinary
            'options' => [],
        ],

        // Durées de vie par défaut (en secondes)
        'default_ttl' => [
            'short' => 300,    // 5 minutes
            'medium' => 3600,  // 1 heure
            'long' => 86400,   // 24 heures
            'very_long' => 604800, // 7 jours
        ],

        // Stratégies d'invalidation
        'invalidation' => [
            'strategy' => env('CACHE_INVALIDATION_STRATEGY', 'tag_based'), // tag_based, time_based, manual
            'batch_size' => env('CACHE_INVALIDATION_BATCH_SIZE', 100),
        ],

        // Monitoring et métriques
        'monitoring' => [
            'enabled' => env('CACHE_MONITORING', true),
            'log_slow_operations' => env('CACHE_LOG_SLOW_OPS', true),
            'slow_threshold' => env('CACHE_SLOW_THRESHOLD', 100), // ms
            'track_hit_ratio' => env('CACHE_TRACK_HIT_RATIO', true),
        ],

        // Nettoyage automatique
        'cleanup' => [
            'enabled' => env('CACHE_AUTO_CLEANUP', true),
            'frequency' => env('CACHE_CLEANUP_FREQUENCY', 'daily'), // hourly, daily, weekly
            'max_age' => env('CACHE_MAX_AGE', 2592000), // 30 jours
            'max_size' => env('CACHE_MAX_SIZE', 1073741824), // 1GB
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Warming Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for cache warming strategies
    |
    */

    'warming' => [
        'enabled' => env('CACHE_WARMING_ENABLED', true),
        'schedule' => env('CACHE_WARMING_SCHEDULE', 'daily'),
        'strategies' => [
            'properties' => [
                'featured' => ['limit' => 10],
                'recent' => ['limit' => 20],
                'popular' => ['limit' => 15],
            ],
            'cities' => [
                'popular' => ['limit' => 10],
                'with_properties' => true,
            ],
            'statistics' => [
                'general' => true,
                'daily' => true,
                'weekly' => false,
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Environment-Specific Cache Settings
    |--------------------------------------------------------------------------
    |
    | Different cache configurations based on environment
    |
    */

    'environments' => [
        'local' => [
            'default_ttl' => 300, // 5 minutes pour le développement
            'compression' => false,
            'monitoring' => true,
        ],
        'testing' => [
            'default_ttl' => 60, // 1 minute pour les tests
            'compression' => false,
            'monitoring' => false,
        ],
        'staging' => [
            'default_ttl' => 1800, // 30 minutes
            'compression' => true,
            'monitoring' => true,
        ],
        'production' => [
            'default_ttl' => 3600, // 1 heure
            'compression' => true,
            'monitoring' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Security Settings
    |--------------------------------------------------------------------------
    |
    | Security-related cache configurations
    |
    */

    'security' => [
        // Chiffrement des données sensibles en cache
        'encryption' => [
            'enabled' => env('CACHE_ENCRYPTION', false),
            'key' => env('CACHE_ENCRYPTION_KEY', env('APP_KEY')),
            'cipher' => env('CACHE_ENCRYPTION_CIPHER', 'AES-256-CBC'),
            'sensitive_keys' => [
                'user_sessions',
                'payment_data',
                'personal_info',
            ],
        ],

        // Validation de l'intégrité
        'integrity' => [
            'enabled' => env('CACHE_INTEGRITY_CHECK', false),
            'algorithm' => env('CACHE_INTEGRITY_ALGORITHM', 'sha256'),
        ],

        // Limitation d'accès
        'access_control' => [
            'enabled' => env('CACHE_ACCESS_CONTROL', false),
            'allowed_ips' => explode(',', env('CACHE_ALLOWED_IPS', '')),
            'rate_limiting' => [
                'enabled' => env('CACHE_RATE_LIMITING', false),
                'max_requests' => env('CACHE_MAX_REQUESTS', 1000),
                'window' => env('CACHE_RATE_WINDOW', 3600), // 1 heure
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Performance Tuning
    |--------------------------------------------------------------------------
    |
    | Advanced performance settings for cache optimization
    |
    */

    'performance' => [
        // Connection pooling pour Redis/Memcached
        'connection_pooling' => [
            'enabled' => env('CACHE_CONNECTION_POOLING', false),
            'min_connections' => env('CACHE_MIN_CONNECTIONS', 1),
            'max_connections' => env('CACHE_MAX_CONNECTIONS', 10),
            'idle_timeout' => env('CACHE_IDLE_TIMEOUT', 300),
        ],

        // Pipelining pour Redis
        'pipelining' => [
            'enabled' => env('CACHE_PIPELINING', false),
            'batch_size' => env('CACHE_PIPELINE_BATCH_SIZE', 100),
        ],

        // Sharding pour distribution de charge
        'sharding' => [
            'enabled' => env('CACHE_SHARDING', false),
            'strategy' => env('CACHE_SHARDING_STRATEGY', 'consistent_hash'),
            'shards' => explode(',', env('CACHE_SHARDS', '')),
        ],

        // Réplication pour haute disponibilité
        'replication' => [
            'enabled' => env('CACHE_REPLICATION', false),
            'read_preference' => env('CACHE_READ_PREFERENCE', 'primary'),
            'replicas' => explode(',', env('CACHE_REPLICAS', '')),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Debugging and Development
    |--------------------------------------------------------------------------
    |
    | Settings for debugging and development purposes
    |
    */

    'debug' => [
        'enabled' => env('CACHE_DEBUG', env('APP_DEBUG', false)),
        'log_operations' => env('CACHE_LOG_OPERATIONS', false),
        'log_level' => env('CACHE_LOG_LEVEL', 'debug'),
        'profiling' => [
            'enabled' => env('CACHE_PROFILING', false),
            'detailed' => env('CACHE_PROFILING_DETAILED', false),
        ],
        'testing' => [
            'fake_driver' => env('CACHE_FAKE_DRIVER', 'array'),
            'clear_between_tests' => env('CACHE_CLEAR_BETWEEN_TESTS', true),
        ],
    ],

];
