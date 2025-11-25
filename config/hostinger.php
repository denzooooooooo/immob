<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Configuration Hostinger
    |--------------------------------------------------------------------------
    |
    | Configuration spécifique pour l'hébergement Hostinger
    |
    */

    'environment' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Optimisations de Performance
    |--------------------------------------------------------------------------
    |
    | Configurations pour optimiser les performances sur Hostinger
    |
    */

    'performance' => [
        // Cache des vues activé en production
        'view_cache' => env('APP_ENV') === 'production',
        
        // Cache des routes activé en production
        'route_cache' => env('APP_ENV') === 'production',
        
        // Cache de configuration activé en production
        'config_cache' => env('APP_ENV') === 'production',
        
        // Optimisation de l'autoloader
        'optimize_autoloader' => true,
        
        // Compression des réponses
        'gzip_compression' => true,
        
        // Cache des assets
        'asset_cache_duration' => 31536000, // 1 an
        
        // Préchargement des ressources critiques
        'preload_critical_resources' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuration Base de Données
    |--------------------------------------------------------------------------
    |
    | Optimisations spécifiques pour MySQL sur Hostinger
    |
    */

    'database' => [
        // Pool de connexions
        'connection_pool' => [
            'min_connections' => 1,
            'max_connections' => 10,
            'idle_timeout' => 300,
        ],
        
        // Cache des requêtes
        'query_cache' => [
            'enabled' => true,
            'default_ttl' => 3600, // 1 heure
        ],
        
        // Optimisations MySQL
        'mysql_optimizations' => [
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'strict_mode' => true,
            'engine' => 'InnoDB',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuration des Sessions
    |--------------------------------------------------------------------------
    |
    | Configuration optimisée pour les sessions sur Hostinger
    |
    */

    'session' => [
        'driver' => 'database',
        'lifetime' => 120,
        'expire_on_close' => true,
        'encrypt' => true,
        'files' => storage_path('framework/sessions'),
        'connection' => null,
        'table' => 'sessions',
        'store' => null,
        'lottery' => [2, 100],
        'cookie' => env('SESSION_COOKIE', 'monnkama_session'),
        'path' => '/',
        'domain' => env('SESSION_DOMAIN', null),
        'secure' => env('SESSION_SECURE_COOKIE', true),
        'http_only' => true,
        'same_site' => 'lax',
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuration du Cache
    |--------------------------------------------------------------------------
    |
    | Configuration du cache optimisée pour Hostinger
    |
    */

    'cache' => [
        'default_store' => 'database',
        'stores' => [
            'database' => [
                'driver' => 'database',
                'table' => 'cache',
                'connection' => null,
                'prefix' => 'monnkama_cache',
            ],
            'file' => [
                'driver' => 'file',
                'path' => storage_path('framework/cache/data'),
            ],
        ],
        'prefix' => 'monnkama',
        'ttl' => [
            'short' => 300,    // 5 minutes
            'medium' => 3600,  // 1 heure
            'long' => 86400,   // 24 heures
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuration des Logs
    |--------------------------------------------------------------------------
    |
    | Configuration des logs optimisée pour Hostinger
    |
    */

    'logging' => [
        'default' => 'daily',
        'channels' => [
            'daily' => [
                'driver' => 'daily',
                'path' => storage_path('logs/laravel.log'),
                'level' => env('LOG_LEVEL', 'error'),
                'days' => 7,
                'replace_placeholders' => true,
            ],
            'security' => [
                'driver' => 'daily',
                'path' => storage_path('logs/security.log'),
                'level' => 'info',
                'days' => 30,
            ],
            'payment' => [
                'driver' => 'daily',
                'path' => storage_path('logs/payment.log'),
                'level' => 'info',
                'days' => 90,
            ],
        ],
        'deprecations' => [
            'channel' => null,
            'trace' => false,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuration des Emails
    |--------------------------------------------------------------------------
    |
    | Configuration SMTP pour Hostinger
    |
    */

    'mail' => [
        'default' => 'smtp',
        'mailers' => [
            'smtp' => [
                'transport' => 'smtp',
                'host' => 'smtp.hostinger.com',
                'port' => 587,
                'encryption' => 'tls',
                'username' => env('MAIL_USERNAME'),
                'password' => env('MAIL_PASSWORD'),
                'timeout' => null,
                'local_domain' => env('MAIL_EHLO_DOMAIN'),
            ],
        ],
        'from' => [
            'address' => env('MAIL_FROM_ADDRESS', 'noreply@monnkama.ga'),
            'name' => env('MAIL_FROM_NAME', 'Monnkama'),
        ],
        'markdown' => [
            'theme' => 'default',
            'paths' => [
                resource_path('views/vendor/mail'),
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuration des Files d'Attente
    |--------------------------------------------------------------------------
    |
    | Configuration des queues pour Hostinger
    |
    */

    'queue' => [
        'default' => 'database',
        'connections' => [
            'database' => [
                'driver' => 'database',
                'table' => 'jobs',
                'queue' => 'default',
                'retry_after' => 90,
                'after_commit' => false,
            ],
        ],
        'batching' => [
            'database' => env('DB_CONNECTION', 'mysql'),
            'table' => 'job_batches',
        ],
        'failed' => [
            'driver' => 'database-uuids',
            'database' => env('DB_CONNECTION', 'mysql'),
            'table' => 'failed_jobs',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuration de Sécurité
    |--------------------------------------------------------------------------
    |
    | Paramètres de sécurité pour l'environnement de production
    |
    */

    'security' => [
        // Protection CSRF
        'csrf' => [
            'enabled' => true,
            'token_lifetime' => 3600, // 1 heure
        ],
        
        // Rate limiting
        'rate_limiting' => [
            'enabled' => true,
            'api_limit' => 60, // requêtes par minute
            'web_limit' => 1000, // requêtes par heure
        ],
        
        // Headers de sécurité
        'headers' => [
            'hsts' => true,
            'csp' => true,
            'xss_protection' => true,
            'content_type_options' => true,
            'frame_options' => 'DENY',
        ],
        
        // Validation des uploads
        'uploads' => [
            'max_size' => 5120, // 5MB
            'allowed_types' => ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf'],
            'scan_viruses' => false, // Désactivé sur Hostinger
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuration des Assets
    |--------------------------------------------------------------------------
    |
    | Configuration pour l'optimisation des assets
    |
    */

    'assets' => [
        // Versioning des assets
        'versioning' => true,
        
        // Compression
        'compression' => [
            'css' => true,
            'js' => true,
            'images' => true,
        ],
        
        // CDN (si configuré)
        'cdn' => [
            'enabled' => env('CDN_ENABLED', false),
            'url' => env('CDN_URL'),
        ],
        
        // Lazy loading
        'lazy_loading' => true,
        
        // Preload des ressources critiques
        'preload' => [
            'fonts' => true,
            'critical_css' => true,
            'hero_images' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuration de Monitoring
    |--------------------------------------------------------------------------
    |
    | Configuration pour le monitoring et les alertes
    |
    */

    'monitoring' => [
        // Sentry pour le monitoring d'erreurs
        'sentry' => [
            'enabled' => env('SENTRY_LARAVEL_DSN') !== null,
            'dsn' => env('SENTRY_LARAVEL_DSN'),
            'environment' => env('SENTRY_ENVIRONMENT', 'production'),
            'sample_rate' => 0.1,
        ],
        
        // Métriques de performance
        'performance' => [
            'track_slow_queries' => true,
            'slow_query_threshold' => 1000, // ms
            'track_memory_usage' => true,
            'track_response_time' => true,
        ],
        
        // Alertes
        'alerts' => [
            'email' => env('ADMIN_EMAIL'),
            'slack_webhook' => env('SLACK_WEBHOOK_URL'),
            'error_threshold' => 10, // erreurs par heure
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuration de Maintenance
    |--------------------------------------------------------------------------
    |
    | Configuration pour la maintenance automatique
    |
    */

    'maintenance' => [
        // Nettoyage automatique
        'cleanup' => [
            'enabled' => true,
            'schedule' => 'daily',
            'retention_days' => 30,
        ],
        
        // Optimisation de la base de données
        'database_optimization' => [
            'enabled' => true,
            'schedule' => 'weekly',
        ],
        
        // Sauvegarde
        'backup' => [
            'enabled' => env('BACKUP_ENABLED', false),
            'schedule' => 'daily',
            'retention_days' => 7,
        ],
        
        // Vérifications de santé
        'health_checks' => [
            'enabled' => true,
            'schedule' => 'hourly',
            'endpoints' => [
                'database',
                'cache',
                'storage',
                'mail',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Limites et Quotas
    |--------------------------------------------------------------------------
    |
    | Configuration des limites pour éviter les abus
    |
    */

    'limits' => [
        // Limites par utilisateur
        'user' => [
            'max_properties' => 50,
            'max_images_per_property' => 20,
            'max_videos_per_property' => 5,
            'max_daily_uploads' => 100,
        ],
        
        // Limites système
        'system' => [
            'max_concurrent_users' => 1000,
            'max_database_connections' => 10,
            'max_memory_usage' => '256M',
            'max_execution_time' => 30,
        ],
        
        // Limites de stockage
        'storage' => [
            'max_total_size' => '10GB',
            'max_file_size' => '5MB',
            'cleanup_threshold' => '8GB',
        ],
    ],

];
