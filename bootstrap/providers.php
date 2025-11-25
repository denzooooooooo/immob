<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Application Service Providers
    |--------------------------------------------------------------------------
    |
    | Les providers listés ici seront automatiquement chargés lors du démarrage
    | de l'application. N'hésitez pas à ajouter vos propres providers dans
    | cette liste selon les besoins de votre application.
    |
    */

    // Providers Laravel par défaut
    Illuminate\Auth\AuthServiceProvider::class,
    Illuminate\Broadcasting\BroadcastServiceProvider::class,
    Illuminate\Bus\BusServiceProvider::class,
    Illuminate\Cache\CacheServiceProvider::class,
    Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
    Illuminate\Cookie\CookieServiceProvider::class,
    Illuminate\Database\DatabaseServiceProvider::class,
    Illuminate\Encryption\EncryptionServiceProvider::class,
    Illuminate\Filesystem\FilesystemServiceProvider::class,
    Illuminate\Foundation\Providers\FoundationServiceProvider::class,
    Illuminate\Hashing\HashServiceProvider::class,
    Illuminate\Mail\MailServiceProvider::class,
    Illuminate\Notifications\NotificationServiceProvider::class,
    Illuminate\Pagination\PaginationServiceProvider::class,
    Illuminate\Pipeline\PipelineServiceProvider::class,
    Illuminate\Queue\QueueServiceProvider::class,
    Illuminate\Redis\RedisServiceProvider::class,
    Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
    Illuminate\Session\SessionServiceProvider::class,
    Illuminate\Translation\TranslationServiceProvider::class,
    Illuminate\Validation\ValidationServiceProvider::class,
    Illuminate\View\ViewServiceProvider::class,

    /*
    |--------------------------------------------------------------------------
    | Package Service Providers
    |--------------------------------------------------------------------------
    |
    | Providers des packages tiers utilisés dans l'application.
    |
    */
    
    Intervention\Image\ImageServiceProvider::class,
    Laravel\Sanctum\SanctumServiceProvider::class,
    Spatie\Permission\PermissionServiceProvider::class,

    /*
    |--------------------------------------------------------------------------
    | Application Service Providers
    |--------------------------------------------------------------------------
    |
    | Providers spécifiques à l'application Monnkama.
    |
    */

    // Provider principal de l'application
    App\Providers\AppServiceProvider::class,

    // Provider pour l'authentification
    App\Providers\AuthServiceProvider::class,

    // Provider pour les événements
    App\Providers\EventServiceProvider::class,

    // Provider pour les routes
    App\Providers\RouteServiceProvider::class,

    // Provider pour les services personnalisés
    App\Providers\CustomServiceProvider::class,

    // Provider pour les repositories
    App\Providers\RepositoryServiceProvider::class,

    // Provider pour les services d'administration
    App\Providers\AdminServiceProvider::class,

    // Provider pour les services d'agent immobilier
    App\Providers\AgentServiceProvider::class,

    // Provider pour les services de propriété
    App\Providers\PropertyServiceProvider::class,

    // Provider pour les services de souscription
    App\Providers\SubscriptionServiceProvider::class,

    // Provider pour les services de messagerie
    App\Providers\MessageServiceProvider::class,

    // Provider pour les services de localisation
    App\Providers\LocationServiceProvider::class,

    // Provider pour les services de notification
    App\Providers\NotificationServiceProvider::class,

    // Provider pour les services de statistiques
    App\Providers\StatisticsServiceProvider::class,

    // Provider pour les services de médias
    App\Providers\MediaServiceProvider::class,

    // Provider pour les services de cache
    App\Providers\CacheServiceProvider::class,

    // Provider pour les services de recherche
    App\Providers\SearchServiceProvider::class,

    // Provider pour les services de paiement
    App\Providers\PaymentServiceProvider::class,

    // Provider pour les services d'export
    App\Providers\ExportServiceProvider::class,

    // Provider pour les services de rapport
    App\Providers\ReportServiceProvider::class,

    // Provider pour les services de validation personnalisée
    App\Providers\ValidationServiceProvider::class,

    // Provider pour les services de sécurité
    App\Providers\SecurityServiceProvider::class,

    // Provider pour les services d'optimisation
    App\Providers\OptimizationServiceProvider::class,

    // Provider pour les services de journalisation
    App\Providers\LoggingServiceProvider::class,

    // Provider pour les services de maintenance
    App\Providers\MaintenanceServiceProvider::class,

    // Provider pour les paramètres du site
    App\Providers\SiteSettingServiceProvider::class,
];
