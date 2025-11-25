<?php

namespace App\Providers;

use App\Models\SiteSetting;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;

class SiteSettingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Partager les paramètres du site avec toutes les vues
        View::composer('*', function ($view) {
            $siteSettings = Cache::remember('site_settings', 3600, function () {
                return SiteSetting::pluck('value', 'key')->toArray();
            });
            
            $view->with('siteSettings', $siteSettings);
        });

        // La fonction helper est maintenant dans app/helpers.php
    }
}
