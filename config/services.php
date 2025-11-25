<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
        'webhook_url' => env('SLACK_WEBHOOK_URL'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Payment Services Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for various payment gateways used in the application
    |
    */

    'stripe' => [
        'model' => App\Models\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'webhook' => [
            'secret' => env('STRIPE_WEBHOOK_SECRET'),
            'tolerance' => env('STRIPE_WEBHOOK_TOLERANCE', 300),
        ],
        'currency' => env('STRIPE_CURRENCY', 'xaf'),
        'logger' => env('STRIPE_LOGGER'),
    ],

    'paypal' => [
        'mode' => env('PAYPAL_MODE', 'sandbox'), // sandbox or live
        'sandbox' => [
            'client_id' => env('PAYPAL_SANDBOX_CLIENT_ID'),
            'client_secret' => env('PAYPAL_SANDBOX_CLIENT_SECRET'),
            'app_id' => env('PAYPAL_SANDBOX_APP_ID'),
        ],
        'live' => [
            'client_id' => env('PAYPAL_LIVE_CLIENT_ID'),
            'client_secret' => env('PAYPAL_LIVE_CLIENT_SECRET'),
            'app_id' => env('PAYPAL_LIVE_APP_ID'),
        ],
        'payment_action' => env('PAYPAL_PAYMENT_ACTION', 'Sale'), // Sale, Authorization, Order
        'currency' => env('PAYPAL_CURRENCY', 'XAF'),
        'notify_url' => env('PAYPAL_NOTIFY_URL'),
        'locale' => env('PAYPAL_LOCALE', 'fr_FR'),
        'validate_ssl' => env('PAYPAL_VALIDATE_SSL', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Mobile Money Services (Gabon)
    |--------------------------------------------------------------------------
    |
    | Configuration for mobile money services available in Gabon
    |
    */

    'orange_money' => [
        'merchant_key' => env('ORANGE_MONEY_MERCHANT_KEY'),
        'merchant_id' => env('ORANGE_MONEY_MERCHANT_ID'),
        'api_url' => env('ORANGE_MONEY_API_URL', 'https://api.orange.com/orange-money-webpay/gn/v1'),
        'return_url' => env('ORANGE_MONEY_RETURN_URL'),
        'cancel_url' => env('ORANGE_MONEY_CANCEL_URL'),
        'notify_url' => env('ORANGE_MONEY_NOTIFY_URL'),
        'currency' => 'XAF',
        'country_code' => 'GA',
        'language' => 'fr',
        'timeout' => 30,
    ],

    'mtn_momo' => [
        'api_url' => env('MTN_MOMO_API_URL', 'https://sandbox.momodeveloper.mtn.com'),
        'primary_key' => env('MTN_MOMO_PRIMARY_KEY'),
        'secondary_key' => env('MTN_MOMO_SECONDARY_KEY'),
        'user_id' => env('MTN_MOMO_USER_ID'),
        'api_key' => env('MTN_MOMO_API_KEY'),
        'callback_url' => env('MTN_MOMO_CALLBACK_URL'),
        'environment' => env('MTN_MOMO_ENVIRONMENT', 'sandbox'), // sandbox or live
        'currency' => 'XAF',
        'timeout' => 30,
    ],

    'airtel_money' => [
        'client_id' => env('AIRTEL_MONEY_CLIENT_ID'),
        'client_secret' => env('AIRTEL_MONEY_CLIENT_SECRET'),
        'api_url' => env('AIRTEL_MONEY_API_URL', 'https://openapiuat.airtel.africa'),
        'callback_url' => env('AIRTEL_MONEY_CALLBACK_URL'),
        'environment' => env('AIRTEL_MONEY_ENVIRONMENT', 'staging'), // staging or live
        'currency' => 'XAF',
        'country' => 'GA',
        'timeout' => 30,
    ],

    /*
    |--------------------------------------------------------------------------
    | Social Authentication Services
    |--------------------------------------------------------------------------
    |
    | Configuration for social login providers
    |
    */

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI'),
        'scopes' => ['openid', 'profile', 'email'],
        'with' => ['openid', 'profile', 'email'],
    ],

    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
        'redirect' => env('FACEBOOK_REDIRECT_URI'),
        'default_graph_version' => 'v18.0',
        'scopes' => ['email', 'public_profile'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Mapping and Geocoding Services
    |--------------------------------------------------------------------------
    |
    | Configuration for location-based services
    |
    */

    'google_maps' => [
        'api_key' => env('GOOGLE_MAPS_API_KEY'),
        'geocoding_api_url' => 'https://maps.googleapis.com/maps/api/geocode/json',
        'places_api_url' => 'https://maps.googleapis.com/maps/api/place',
        'default_country' => 'GA',
        'default_language' => 'fr',
    ],

    'mapbox' => [
        'access_token' => env('MAPBOX_ACCESS_TOKEN'),
        'api_url' => 'https://api.mapbox.com',
        'default_style' => 'mapbox://styles/mapbox/streets-v11',
    ],

    /*
    |--------------------------------------------------------------------------
    | File Storage Services
    |--------------------------------------------------------------------------
    |
    | Configuration for cloud storage services
    |
    */

    'aws' => [
        'credentials' => [
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'token' => env('AWS_SESSION_TOKEN'),
        ],
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
        'version' => 'latest',
        'ua_append' => [
            'L5MOD/' . env('APP_VERSION', '1.0.0'),
        ],
        'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
        'throw' => false,
    ],

    'cloudinary' => [
        'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
        'api_key' => env('CLOUDINARY_API_KEY'),
        'api_secret' => env('CLOUDINARY_API_SECRET'),
        'secure' => true,
        'upload_preset' => env('CLOUDINARY_UPLOAD_PRESET'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Communication Services
    |--------------------------------------------------------------------------
    |
    | Configuration for SMS, email and other communication services
    |
    */

    'twilio' => [
        'sid' => env('TWILIO_SID'),
        'token' => env('TWILIO_TOKEN'),
        'from' => env('TWILIO_FROM'),
        'verify_sid' => env('TWILIO_VERIFY_SID'),
    ],

    'nexmo' => [
        'key' => env('NEXMO_KEY'),
        'secret' => env('NEXMO_SECRET'),
        'sms_from' => env('NEXMO_SMS_FROM', 'Monnkama'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Analytics and Monitoring Services
    |--------------------------------------------------------------------------
    |
    | Configuration for analytics and monitoring tools
    |
    */

    'google_analytics' => [
        'tracking_id' => env('GOOGLE_ANALYTICS_ID'),
        'view_id' => env('GOOGLE_ANALYTICS_VIEW_ID'),
        'service_account_credentials_json' => env('GOOGLE_SERVICE_ACCOUNT_CREDENTIALS_JSON'),
    ],

    'facebook_pixel' => [
        'pixel_id' => env('FACEBOOK_PIXEL_ID'),
        'access_token' => env('FACEBOOK_PIXEL_ACCESS_TOKEN'),
    ],

    'sentry' => [
        'dsn' => env('SENTRY_LARAVEL_DSN'),
        'environment' => env('SENTRY_ENVIRONMENT', env('APP_ENV')),
        'release' => env('SENTRY_RELEASE'),
        'traces_sample_rate' => (float) env('SENTRY_TRACES_SAMPLE_RATE', 0.1),
    ],

    /*
    |--------------------------------------------------------------------------
    | Search and Indexing Services
    |--------------------------------------------------------------------------
    |
    | Configuration for search engines and indexing services
    |
    */

    'elasticsearch' => [
        'hosts' => [
            env('ELASTICSEARCH_HOST', 'localhost:9200'),
        ],
        'username' => env('ELASTICSEARCH_USERNAME'),
        'password' => env('ELASTICSEARCH_PASSWORD'),
        'cloud_id' => env('ELASTICSEARCH_CLOUD_ID'),
        'api_key' => env('ELASTICSEARCH_API_KEY'),
        'ssl_verification' => env('ELASTICSEARCH_SSL_VERIFICATION', true),
    ],

    'algolia' => [
        'app_id' => env('ALGOLIA_APP_ID'),
        'secret' => env('ALGOLIA_SECRET'),
        'search_key' => env('ALGOLIA_SEARCH_KEY'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Content Delivery and Optimization
    |--------------------------------------------------------------------------
    |
    | Configuration for CDN and optimization services
    |
    */

    'cloudflare' => [
        'api_token' => env('CLOUDFLARE_API_TOKEN'),
        'zone_id' => env('CLOUDFLARE_ZONE_ID'),
        'email' => env('CLOUDFLARE_EMAIL'),
        'key' => env('CLOUDFLARE_KEY'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Backup and Recovery Services
    |--------------------------------------------------------------------------
    |
    | Configuration for backup services
    |
    */

    'backup' => [
        'notifications' => [
            'slack' => [
                'webhook_url' => env('BACKUP_SLACK_WEBHOOK_URL'),
                'channel' => env('BACKUP_SLACK_CHANNEL', '#backups'),
                'username' => env('BACKUP_SLACK_USERNAME', 'Backup Bot'),
            ],
            'mail' => [
                'to' => env('BACKUP_MAIL_TO', 'admin@monnkama.ga'),
                'from' => [
                    'address' => env('BACKUP_MAIL_FROM', 'noreply@monnkama.ga'),
                    'name' => env('BACKUP_MAIL_FROM_NAME', 'Monnkama Backup'),
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Security and Validation Services
    |--------------------------------------------------------------------------
    |
    | Configuration for security-related services
    |
    */

    'recaptcha' => [
        'site_key' => env('RECAPTCHA_SITE_KEY'),
        'secret_key' => env('RECAPTCHA_SECRET_KEY'),
        'version' => env('RECAPTCHA_VERSION', 'v2'),
        'threshold' => env('RECAPTCHA_THRESHOLD', 0.5),
    ],

    'akismet' => [
        'api_key' => env('AKISMET_API_KEY'),
        'blog_url' => env('APP_URL'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Development and Testing Services
    |--------------------------------------------------------------------------
    |
    | Configuration for development tools
    |
    */

    'mailpit' => [
        'host' => env('MAILPIT_HOST', 'localhost'),
        'port' => env('MAILPIT_PORT', 1025),
        'encryption' => env('MAILPIT_ENCRYPTION'),
    ],

    'telescope' => [
        'enabled' => env('TELESCOPE_ENABLED', false),
        'path' => env('TELESCOPE_PATH', 'telescope'),
        'driver' => env('TELESCOPE_DRIVER', 'database'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Application Services
    |--------------------------------------------------------------------------
    |
    | Configuration for custom services specific to the application
    |
    */

    'monnkama' => [
        'api_version' => env('API_VERSION', 'v1'),
        'max_properties_per_user' => env('MAX_PROPERTIES_PER_USER', 50),
        'max_images_per_property' => env('MAX_IMAGES_PER_PROPERTY', 20),
        'max_videos_per_property' => env('MAX_VIDEOS_PER_PROPERTY', 5),
        'property_approval_required' => env('PROPERTY_APPROVAL_REQUIRED', true),
        'commission_rate' => env('COMMISSION_RATE', 5.0),
        'currency' => env('DEFAULT_CURRENCY', 'XAF'),
        'supported_currencies' => ['XAF', 'EUR', 'USD'],
        'supported_languages' => ['fr', 'en'],
        'default_language' => env('DEFAULT_LANGUAGE', 'fr'),
        'contact_email' => env('CONTACT_EMAIL', 'contact@monnkama.ga'),
        'support_email' => env('SUPPORT_EMAIL', 'support@monnkama.ga'),
        'admin_email' => env('ADMIN_EMAIL', 'admin@monnkama.ga'),
        'phone' => env('CONTACT_PHONE', '+241 06 05 22 63'),
        'address' => env('CONTACT_ADDRESS', 'Libreville, Gabon'),
        'social_links' => [
            'facebook' => env('FACEBOOK_URL'),
            'twitter' => env('TWITTER_URL'),
            'instagram' => env('INSTAGRAM_URL'),
            'linkedin' => env('LINKEDIN_URL'),
            'youtube' => env('YOUTUBE_URL'),
        ],
        'features' => [
            'multi_language' => env('FEATURE_MULTI_LANGUAGE', false),
            'social_login' => env('FEATURE_SOCIAL_LOGIN', true),
            'mobile_money' => env('FEATURE_MOBILE_MONEY', true),
            'property_comparison' => env('FEATURE_PROPERTY_COMPARISON', true),
            'saved_searches' => env('FEATURE_SAVED_SEARCHES', true),
            'property_alerts' => env('FEATURE_PROPERTY_ALERTS', true),
            'virtual_tours' => env('FEATURE_VIRTUAL_TOURS', false),
            'mortgage_calculator' => env('FEATURE_MORTGAGE_CALCULATOR', true),
        ],
    ],

];
