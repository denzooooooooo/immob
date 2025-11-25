<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SiteSettingController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::getAllGrouped();
        
        return view('admin.settings.site', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'settings' => 'required|array',
            'settings.*' => 'nullable',
            'files.*' => 'nullable|image|max:2048'
        ]);

        foreach ($validated['settings'] as $key => $value) {
            $setting = SiteSetting::where('key', $key)->first();
            
            if (!$setting) {
                continue;
            }

            // Gestion des fichiers images
            if ($setting->type === 'image' && $request->hasFile("files.{$key}")) {
                // Supprimer l'ancienne image si elle existe
                if ($setting->value) {
                    Storage::disk('public')->delete($setting->value);
                }

                // Stocker la nouvelle image
                $path = $request->file("files.{$key}")->store('site-settings', 'public');
                $value = $path;
            }

            // Mise à jour de la valeur
            $setting->update(['value' => $value]);
        }

        SiteSetting::clearCache();

        return redirect()
            ->route('admin.settings.site')
            ->with('success', 'Les paramètres du site ont été mis à jour avec succès.');
    }

    public function deleteImage(Request $request, string $key)
    {
        $setting = SiteSetting::where('key', $key)->firstOrFail();

        if ($setting->type !== 'image') {
            return response()->json(['error' => 'Ce paramètre n\'est pas une image.'], 400);
        }

        if ($setting->value) {
            Storage::disk('public')->delete($setting->value);
            $setting->update(['value' => null]);
            SiteSetting::clearCache();
        }

        return response()->json(['message' => 'Image supprimée avec succès.']);
    }
}
