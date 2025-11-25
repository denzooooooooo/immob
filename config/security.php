<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Security Headers
    |--------------------------------------------------------------------------
    |
    | Configuration for security headers added by SecurityHeaders middleware
    |
    */

    'headers' => [
        'enabled' => env('SECURITY_HEADERS_ENABLED', true),
        
        // Content Security Policy (CSP)
        'csp' => [
            'enabled' => env('CSP_ENABLED', true),
            'report_only' => env('CSP_REPORT_ONLY', false),
            'report_uri' => env('CSP_REPORT_URI'),
            'policies' => [
                'default-src' => ["'self'"],
                'script-src' => [
                    "'self'",
                    "'unsafe-inline'",
                    "'unsafe-eval'",
                    'https://cdn.tailwindcss.com',
                    'https://unpkg.com',
                    'https://cdnjs.cloudflare.com',
                    'https://www.google.com',
                    'https://www.gstatic.com',
                ],
                'style-src' => [
                    "'self'",
                    "'unsafe-inline'",
                    'https://fonts.googleapis.com',
                    'https://cdnjs.cloudflare.com',
                ],
                'font-src' => [
                    "'self'",
                    'https://fonts.gstatic.com',
                    'https://cdnjs.cloudflare.com',
                ],
                'img-src' => ["'self'", 'data:', 'https:', 'blob:'],
                'media-src' => ["'self'", 'https:'],
                'connect-src' => [
                    "'self'",
                    'https://api.stripe.com',
                    'https://api.paypal.com',
                    'https://api.orange.com',
                    'https://sandbox.momodeveloper.mtn.com',
                    'https://openapiuat.airtel.africa',
                ],
                'object-src' => ["'none'"],
                'base-uri' => ["'self'"],
                'form-action' => ["'self'"],
                'frame-ancestors' => ["'none'"],
                'upgrade-insecure-requests' => true,
            ],
        ],

        // Cross-Origin Resource Sharing (CORS)
        'cors' => [
            'enabled' => env('CORS_ENABLED', true),
            'paths' => ['api/*'],
            'allowed_methods' => ['*'],
            'allowed_origins' => [env('APP_URL')],
            'allowed_origins_patterns' => [],
            'allowed_headers' => ['*'],
            'exposed_headers' => [],
            'max_age' => 0,
            'supports_credentials' => false,
        ],

        // Other Security Headers
        'x_frame_options' => 'DENY',
        'x_content_type_options' => 'nosniff',
        'x_xss_protection' => '1; mode=block',
        'referrer_policy' => 'strict-origin-when-cross-origin',
        'permissions_policy' => [
            'accelerometer' => [],
            'ambient-light-sensor' => [],
            'autoplay' => [],
            'battery' => [],
            'camera' => [],
            'display-capture' => [],
            'document-domain' => [],
            'encrypted-media' => [],
            'fullscreen' => ["'self'"],
            'geolocation' => ["'self'"],
            'gyroscope' => [],
            'magnetometer' => [],
            'microphone' => [],
            'midi' => [],
            'payment' => ["'self'"],
            'picture-in-picture' => [],
            'usb' => [],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting
    |--------------------------------------------------------------------------
    |
    | Configuration for rate limiting and throttling
    |
    */

    'rate_limiting' => [
        'enabled' => env('RATE_LIMITING_ENABLED', true),
        
        // API Rate Limits
        'api' => [
            'enabled' => true,
            'max_attempts' => env('API_RATE_LIMIT', 60),
            'decay_minutes' => 1,
            'prefix' => 'api_limit:',
        ],
        
        // Login Attempts
        'login' => [
            'enabled' => true,
            'max_attempts' => 5,
            'decay_minutes' => 15,
            'prefix' => 'login_limit:',
        ],
        
        // Password Reset
        'password_reset' => [
            'enabled' => true,
            'max_attempts' => 3,
            'decay_minutes' => 60,
            'prefix' => 'reset_limit:',
        ],
        
        // Contact Form
        'contact' => [
            'enabled' => true,
            'max_attempts' => 5,
            'decay_minutes' => 30,
            'prefix' => 'contact_limit:',
        ],
        
        // Property Search
        'search' => [
            'enabled' => true,
            'max_attempts' => 50,
            'decay_minutes' => 1,
            'prefix' => 'search_limit:',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Security
    |--------------------------------------------------------------------------
    |
    | Settings related to authentication security
    |
    */

    'authentication' => [
        // Password Requirements
        'password_requirements' => [
            'min_length' => 8,
            'require_uppercase' => true,
            'require_numeric' => true,
            'require_special_chars' => true,
            'prevent_common_passwords' => true,
        ],
        
        // Session Security
        'session' => [
            'regenerate_on_login' => true,
            'expire_on_close' => true,
            'http_only' => true,
            'secure' => env('SESSION_SECURE_COOKIE', true),
            'same_site' => 'lax',
        ],
        
        // Two Factor Authentication
        '2fa' => [
            'enabled' => env('2FA_ENABLED', false),
            'provider' => env('2FA_PROVIDER', 'google'),
            'issuer' => env('2FA_ISSUER', 'Monnkama'),
            'required_for_roles' => ['admin', 'agent'],
        ],
        
        // Social Authentication
        'social' => [
            'verify_email' => true,
            'auto_register' => true,
            'remember_login' => false,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | File Upload Security
    |--------------------------------------------------------------------------
    |
    | Configuration for secure file uploads
    |
    */

    'uploads' => [
        // General Settings
        'max_size' => 5120, // 5MB
        'sanitize_filenames' => true,
        'check_mime_types' => true,
        
        // Allowed File Types
        'allowed_types' => [
            'image' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
            'document' => ['pdf', 'doc', 'docx'],
            'video' => ['mp4', 'webm'],
        ],
        
        // Allowed MIME Types
        'allowed_mimes' => [
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/webp',
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'video/mp4',
            'video/webm',
        ],
        
        // Virus Scanning
        'virus_scanning' => [
            'enabled' => env('UPLOAD_VIRUS_SCAN', false),
            'command' => 'clamscan',
            'options' => '--no-summary',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Data Protection
    |--------------------------------------------------------------------------
    |
    | Settings for data protection and privacy
    |
    */

    'data_protection' => [
        // Personal Data
        'personal_data_fields' => [
            'name',
            'email',
            'phone',
            'address',
            'date_of_birth',
            'national_id',
        ],
        
        // Data Retention
        'retention' => [
            'user_data' => 365, // days
            'activity_logs' => 90,
            'error_logs' => 30,
            'backup_files' => 30,
        ],
        
        // Data Encryption
        'encryption' => [
            'algorithm' => 'AES-256-CBC',
            'key' => env('APP_KEY'),
        ],
        
        // Data Anonymization
        'anonymization' => [
            'enabled' => true,
            'strategy' => 'pseudonymize', // pseudonymize, randomize, nullify
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Security Monitoring
    |--------------------------------------------------------------------------
    |
    | Configuration for security monitoring and logging
    |
    */

    'monitoring' => [
        // Activity Logging
        'activity_log' => [
            'enabled' => true,
            'log_authenticated_actions' => true,
            'log_model_events' => true,
            'log_console_commands' => true,
        ],
        
        // Security Events
        'events' => [
            'log_failed_logins' => true,
            'log_password_resets' => true,
            'log_suspicious_activities' => true,
            'notify_admins' => true,
        ],
        
        // IP Blocking
        'ip_blocking' => [
            'enabled' => true,
            'max_failed_attempts' => 10,
            'block_duration' => 60, // minutes
            'whitelist' => explode(',', env('IP_WHITELIST', '')),
            'blacklist' => explode(',', env('IP_BLACKLIST', '')),
        ],
        
        // Alerts
        'alerts' => [
            'channels' => ['mail', 'slack'],
            'threshold' => [
                'failed_logins' => 5,
                'password_resets' => 3,
                'api_errors' => 10,
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | API Security
    |--------------------------------------------------------------------------
    |
    | Settings for API security
    |
    */

    'api' => [
        // API Authentication
        'auth' => [
            'token_lifetime' => 60, // minutes
            'refresh_token_lifetime' => 1440, // 24 hours
            'require_client_secret' => true,
        ],
        
        // Request Validation
        'validation' => [
            'require_signature' => env('API_REQUIRE_SIGNATURE', false),
            'signature_algorithm' => 'sha256',
            'timestamp_tolerance' => 300, // seconds
        ],
        
        // Response Security
        'response' => [
            'hide_error_details' => env('APP_ENV') === 'production',
            'secure_headers' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Payment Security
    |--------------------------------------------------------------------------
    |
    | Configuration for payment-related security
    |
    */

    'payments' => [
        // General Settings
        'encrypt_card_data' => true,
        'log_payment_attempts' => true,
        
        // Fraud Detection
        'fraud_detection' => [
            'enabled' => true,
            'max_daily_amount' => 1000000, // 1M XAF
            'max_transactions_per_day' => 10,
            'suspicious_patterns' => [
                'multiple_cards',
                'high_frequency',
                'amount_velocity',
            ],
        ],
        
        // PCI Compliance
        'pci' => [
            'enabled' => true,
            'log_sensitive_data' => false,
            'mask_card_numbers' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Development and Testing
    |--------------------------------------------------------------------------
    |
    | Security settings for development and testing environments
    |
    */

    'development' => [
        'debug_mode' => env('APP_DEBUG', false),
        'test_accounts' => [
            'enabled' => env('APP_ENV') !== 'production',
            'password' => env('TEST_ACCOUNT_PASSWORD'),
        ],
        'disable_security_checks' => env('DISABLE_SECURITY_CHECKS', false),
    ],

];
