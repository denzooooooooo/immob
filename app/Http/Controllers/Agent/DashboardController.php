<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Message;
use App\Models\PropertyView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Statistiques de l'agent
        $stats = [
            'total_properties' => $user->properties()->count(),
            'published_properties' => $user->properties()->where('published', true)->count(),
            'featured_properties' => $user->properties()->where('featured', true)->count(),
            'total_views' => PropertyView::whereIn('property_id', $user->properties()->pluck('id'))->count(),
            'total_messages' => Message::whereIn('property_id', $user->properties()->pluck('id'))->count(),
            'unread_messages' => $user->unread_messages_count,
            'views_this_month' => PropertyView::whereIn('property_id', $user->properties()->pluck('id'))
                ->whereMonth('viewed_at', now()->month)
                ->count(),
        ];

        // Propriétés récentes
        $recentProperties = $user->properties()
            ->with(['media', 'views'])
            ->latest()
            ->take(5)
            ->get();

        // Messages récents (messages reçus par l'agent)
        $recentMessages = Message::where('receiver_id', $user->id)
            ->with(['sender', 'property'])
            ->latest()
            ->take(5)
            ->get();

        // Statistiques des vues par mois (6 derniers mois)
        $viewsStats = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $viewsStats[] = [
                'month' => $month->format('M Y'),
                'views' => PropertyView::whereIn('property_id', $user->properties()->pluck('id'))
                    ->whereYear('viewed_at', $month->year)
                    ->whereMonth('viewed_at', $month->month)
                    ->count()
            ];
        }

        // Propriétés les plus vues
        $topProperties = $user->properties()
            ->withCount('views')
            ->orderBy('views_count', 'desc')
            ->take(5)
            ->get();

        // Récupérer l'abonnement actuel
        $subscription = $user->current_subscription;

        // Données pour les graphiques
        $charts = [
            'views_labels' => $viewsStats ? array_column($viewsStats, 'month') : [],
            'views_data' => $viewsStats ? array_column($viewsStats, 'views') : [],
            'messages_labels' => $viewsStats ? array_column($viewsStats, 'month') : [],
            'messages_data' => array_fill(0, count($viewsStats), 0), // Placeholder pour les messages par mois
        ];

        return view('agent.dashboard', compact(
            'stats',
            'recentProperties',
            'recentMessages',
            'viewsStats',
            'topProperties',
            'subscription',
            'charts'
        ));
    }

    public function statistics()
    {
        $user = Auth::user();
        
        // Statistiques détaillées
        $stats = [
            'properties_by_type' => $user->properties()
                ->selectRaw('type, COUNT(*) as count')
                ->groupBy('type')
                ->pluck('count', 'type'),
            
            'properties_by_status' => $user->properties()
                ->selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status'),
                
            'properties_by_city' => $user->properties()
                ->selectRaw('city, COUNT(*) as count')
                ->groupBy('city')
                ->pluck('count', 'city'),
                
            'monthly_revenue' => [], // À implémenter avec le système de paiement
            
            'conversion_rate' => $this->calculateConversionRate($user),
        ];

        return response()->json($stats);
    }

    public function profile()
    {
        $user = Auth::user();
        return view('agent.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'company_name' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:1000',
            'website' => 'nullable|url|max:255',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'email_notifications' => 'boolean',
            'sms_notifications' => 'boolean',
        ]);

        // Upload avatar si fourni
        if ($request->hasFile('avatar')) {
            // Supprimer l'ancien avatar
            if ($user->avatar) {
                \Storage::disk('public')->delete($user->avatar);
            }
            
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($validated);

        return redirect()->route('agent.profile')
            ->with('success', 'Profil mis à jour avec succès !');
    }

    private function calculateConversionRate($user)
    {
        $totalViews = PropertyView::whereIn('property_id', $user->properties()->pluck('id'))->count();
        $totalMessages = Message::whereIn('property_id', $user->properties()->pluck('id'))->count();
        
        if ($totalViews == 0) return 0;
        
        return round(($totalMessages / $totalViews) * 100, 2);
    }
}
