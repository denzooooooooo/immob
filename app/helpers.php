<?php

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Cache;

if (!function_exists('site_setting')) {
    /**
     * Récupérer un paramètre du site
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function site_setting($key, $default = null)
    {
        static $settings = null;
        
        if ($settings === null) {
            $settings = Cache::remember('site_settings', 3600, function () {
                return SiteSetting::pluck('value', 'key')->toArray();
            });
        }
        
        return $settings[$key] ?? $default;
    }
}
