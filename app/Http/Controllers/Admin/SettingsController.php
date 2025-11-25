<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingsController extends Controller
{
    /**
     * Afficher la page des paramètres
     */
    public function index()
    {
        $settings = [
            'site_name' => Cache::get('settings.site_name', 'Monnkama'),
            'contact_email' => Cache::get('settings.contact_email', ''),
            'phone_number' => Cache::get('settings.phone_number', ''),
            'address' => Cache::get('settings.address', ''),
            'facebook_url' => Cache::get('settings.facebook_url', ''),
            'twitter_url' => Cache::get('settings.twitter_url', ''),
            'instagram_url' => Cache::get('settings.instagram_url', ''),
            'maintenance_mode' => Cache::get('settings.maintenance_mode', false),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Mettre à jour les paramètres
     */
    public function update(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'contact_email' => 'required|email',
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'facebook_url' => 'nullable|url',
            'twitter_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'maintenance_mode' => 'boolean',
        ]);

        // Sauvegarder les paramètres dans le cache
        foreach ($request->except(['_token', '_method']) as $key => $value) {
            Cache::forever('settings.' . $key, $value);
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Les paramètres ont été mis à jour avec succès.');
    }
}
