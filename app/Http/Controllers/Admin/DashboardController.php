<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\User;
use App\Models\Message;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistiques générales
        $stats = [
            'total_properties' => Property::count(),
            'active_properties' => Property::where('published', true)->count(),
            'total_users' => User::count(),
            'active_users' => User::where('status', 'active')->count(),
            'total_agents' => User::where('role', 'agent')->count(),
            'active_agents' => User::where('role', 'agent')->where('status', 'active')->count(),
            'total_revenue' => Subscription::sum('price_paid') ?? 0,
            'revenue_month' => Subscription::whereMonth('created_at', now()->month)->sum('price_paid') ?? 0,
            'unread_messages' => Message::whereNull('read_at')->count(),
            'pending_messages' => Message::where('is_system_message', false)->whereNull('read_at')->count(),
            'reported_messages' => Message::where('is_system_message', true)->count(),
            'total_messages' => Message::count(),
            'new_properties_month' => Property::whereMonth('created_at', now()->month)->count(),
            'total_views' => DB::table('property_views')->count(),
            'views_today' => DB::table('property_views')->whereDate('viewed_at', today())->count(),
        ];

        // Propriétés par type
        $propertiesByType = Property::select('type', DB::raw('count(*) as count'))
            ->groupBy('type')
            ->get();

        // Propriétés par statut
        $propertiesByStatus = Property::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        // Revenus mensuels
        $monthlyRevenue = Subscription::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(price_paid) as total')
            )
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get();

        // Dernières propriétés
        $recentProperties = Property::latest()
            ->limit(5)
            ->get();

        // Derniers utilisateurs
        $recentUsers = User::latest()
            ->limit(5)
            ->get();

        // Derniers messages
        $latestMessages = Message::with(['sender', 'receiver', 'property'])
            ->latest()
            ->limit(5)
            ->get();

        // Abonnements actifs par plan
        $subscriptionsByPlan = Subscription::select('plan', DB::raw('count(*) as count'))
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->groupBy('plan')
            ->get();

        // Données pour les graphiques
        $charts = [
            'revenue_labels' => $monthlyRevenue->pluck('month')->map(function($month) {
                return date('M', mktime(0, 0, 0, $month, 1));
            })->reverse()->values(),
            'revenue_data' => $monthlyRevenue->pluck('total')->reverse()->values(),
            'properties_labels' => $propertiesByType->pluck('type'),
            'properties_data' => $propertiesByType->pluck('count'),
        ];

        return view('admin.dashboard', compact(
            'stats',
            'propertiesByType',
            'propertiesByStatus',
            'monthlyRevenue',
            'recentProperties',
            'recentUsers',
            'latestMessages',
            'subscriptionsByPlan',
            'charts'
        ));
    }

    public function analytics()
    {
        // Statistiques détaillées pour une période donnée
        $period = request('period', '30'); // jours par défaut
        $startDate = now()->subDays($period);

        // Vues de propriétés
        $propertyViews = DB::table('property_views')
            ->select(
                DB::raw('DATE(viewed_at) as date'),
                DB::raw('COUNT(*) as views')
            )
            ->where('viewed_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Nouvelles inscriptions
        $newRegistrations = User::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Nouvelles propriétés
        $newProperties = Property::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Revenus
        $revenue = Subscription::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(price_paid) as total')
            )
            ->where('created_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Taux de conversion
        $conversionRate = [
            'views' => DB::table('property_views')
                ->where('viewed_at', '>=', $startDate)
                ->count(),
            'messages' => Message::where('created_at', '>=', $startDate)
                ->count(),
        ];

        return view('admin.analytics', compact(
            'propertyViews',
            'newRegistrations',
            'newProperties',
            'revenue',
            'conversionRate',
            'period'
        ));
    }

    public function systemStatus()
    {
        // État du système
        $status = [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'server' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'database' => DB::connection()->getDatabaseName(),
            'timezone' => config('app.timezone'),
            'environment' => app()->environment(),
            'debug_mode' => config('app.debug'),
            'cache_driver' => config('cache.default'),
            'session_driver' => config('session.driver'),
            'queue_connection' => config('queue.default'),
        ];

        // Espace disque
        $storage = [
            'total' => disk_total_space(base_path()),
            'free' => disk_free_space(base_path()),
        ];

        // Dernières erreurs du log
        $logFile = storage_path('logs/laravel.log');
        $recentErrors = file_exists($logFile) 
            ? array_slice(file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES), -50) 
            : [];

        // État des tâches planifiées
        $scheduledTasks = [
            'backup' => [
                'last_run' => cache('last_backup_run'),
                'status' => cache('last_backup_status'),
            ],
            'cleanup' => [
                'last_run' => cache('last_cleanup_run'),
                'status' => cache('last_cleanup_status'),
            ],
        ];

        return view('admin.system-status', compact(
            'status',
            'storage',
            'recentErrors',
            'scheduledTasks'
        ));
    }
}
