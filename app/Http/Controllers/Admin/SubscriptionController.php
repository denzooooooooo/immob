<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $query = Subscription::with('user');

        // Filtres
        if ($request->filled('plan')) {
            $query->where('plan', $request->plan);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Tri
        $sort = $request->sort ?? 'created_at';
        $direction = $request->direction ?? 'desc';
        $query->orderBy($sort, $direction);

        $subscriptions = $query->paginate(20)->withQueryString();

        // Statistiques
        $stats = [
            'total_revenue' => Subscription::sum('price_paid'),
            'monthly_revenue' => Subscription::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('price_paid'),
            'active_subscriptions' => Subscription::where('status', 'active')
                ->where('expires_at', '>', now())
                ->count(),
            'expiring_soon' => Subscription::where('status', 'active')
                ->whereBetween('expires_at', [now(), now()->addDays(7)])
                ->count(),
            'expired_subscriptions' => Subscription::where('expires_at', '<=', now())
                ->count(),
        ];

        // Données pour le graphique des revenus
        $revenueData = Subscription::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(price_paid) as total')
        )
        ->where('created_at', '>=', now()->subMonths(6))
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        $chart = [
            'labels' => $revenueData->pluck('date')->map(function($date) {
                return \Carbon\Carbon::parse($date)->format('d/m/Y');
            }),
            'data' => $revenueData->pluck('total')
        ];

        return view('admin.subscriptions.index', compact('subscriptions', 'stats', 'chart'));
    }

    public function create()
    {
        $users = User::where('role', '!=', 'admin')
            ->orderBy('name')
            ->get();
        
        $plans = Subscription::getAllPlans();

        return view('admin.subscriptions.create', compact('users', 'plans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'plan' => 'required|in:basic,premium,pro',
            'price_paid' => 'required|numeric|min:0',
            'currency' => 'required|in:XAF,EUR,USD',
            'payment_method' => 'required|in:airtel_money,orange_money,card,bank_transfer',
            'transaction_id' => 'nullable|string|max:255',
            'duration_days' => 'required|integer|min:1',
            'status' => 'required|in:active,pending,cancelled',
        ]);

        DB::beginTransaction();

        try {
            $planDetails = Subscription::getPlanDetails($validated['plan']);

            $subscription = Subscription::create([
                'user_id' => $validated['user_id'],
                'plan' => $validated['plan'],
                'price_paid' => $validated['price_paid'],
                'currency' => $validated['currency'],
                'starts_at' => now(),
                'expires_at' => now()->addDays($validated['duration_days']),
                'status' => $validated['status'],
                'payment_method' => $validated['payment_method'],
                'transaction_id' => $validated['transaction_id'],
                'properties_limit' => $planDetails['properties_limit'],
                'properties_used' => 0,
                'featured_listings' => $planDetails['featured_listings'],
                'priority_support' => $planDetails['priority_support'],
            ]);

            DB::commit();

            return redirect()
                ->route('admin.subscriptions.show', $subscription)
                ->with('success', 'Abonnement créé avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la création de l\'abonnement.');
        }
    }

    public function show(Subscription $subscription)
    {
        $subscription->load('user');
        return view('admin.subscriptions.show', compact('subscription'));
    }

    public function edit(Subscription $subscription)
    {
        $users = User::where('role', '!=', 'admin')
            ->orderBy('name')
            ->get();
        
        $plans = Subscription::getAllPlans();

        return view('admin.subscriptions.edit', compact('subscription', 'users', 'plans'));
    }

    public function update(Request $request, Subscription $subscription)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'plan' => 'required|in:basic,premium,pro',
            'price_paid' => 'required|numeric|min:0',
            'currency' => 'required|in:XAF,EUR,USD',
            'starts_at' => 'required|date',
            'expires_at' => 'required|date|after:starts_at',
            'status' => 'required|in:active,pending,cancelled,expired',
            'payment_method' => 'required|in:airtel_money,orange_money,card,bank_transfer',
            'transaction_id' => 'nullable|string|max:255',
            'properties_limit' => 'required|integer|min:0',
            'properties_used' => 'required|integer|min:0',
            'featured_listings' => 'boolean',
            'priority_support' => 'boolean',
        ]);

        DB::beginTransaction();

        try {
            $subscription->update($validated);

            DB::commit();

            return redirect()
                ->route('admin.subscriptions.show', $subscription)
                ->with('success', 'Abonnement mis à jour avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la mise à jour de l\'abonnement.');
        }
    }

    public function destroy(Subscription $subscription)
    {
        try {
            $subscription->delete();

            return redirect()
                ->route('admin.subscriptions.index')
                ->with('success', 'Abonnement supprimé avec succès.');

        } catch (\Exception $e) {
            return back()->with('error', 'Une erreur est survenue lors de la suppression de l\'abonnement.');
        }
    }

    public function cancel(Subscription $subscription)
    {
        $subscription->update(['status' => 'cancelled']);

        return back()->with('success', 'Abonnement annulé avec succès.');
    }

    public function renew(Request $request, Subscription $subscription)
    {
        $validated = $request->validate([
            'duration_days' => 'required|integer|min:1',
            'price_paid' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            // Créer un nouvel abonnement
            $newSubscription = Subscription::create([
                'user_id' => $subscription->user_id,
                'plan' => $subscription->plan,
                'price_paid' => $validated['price_paid'],
                'currency' => $subscription->currency,
                'starts_at' => $subscription->expires_at,
                'expires_at' => $subscription->expires_at->addDays($validated['duration_days']),
                'status' => 'active',
                'payment_method' => $subscription->payment_method,
                'properties_limit' => $subscription->properties_limit,
                'properties_used' => 0,
                'featured_listings' => $subscription->featured_listings,
                'priority_support' => $subscription->priority_support,
            ]);

            DB::commit();

            return redirect()
                ->route('admin.subscriptions.show', $newSubscription)
                ->with('success', 'Abonnement renouvelé avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Une erreur est survenue lors du renouvellement de l\'abonnement.');
        }
    }

    public function extend(Request $request, Subscription $subscription)
    {
        $validated = $request->validate([
            'days' => 'required|integer|min:1',
        ]);

        $subscription->extend($validated['days']);

        return back()->with('success', 'Abonnement prolongé de ' . $validated['days'] . ' jours.');
    }

    public function analytics()
    {
        // Revenus par mois
        $monthlyRevenue = Subscription::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(price_paid) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get();

        // Revenus par plan
        $revenueByPlan = Subscription::select('plan', DB::raw('SUM(price_paid) as total'))
            ->groupBy('plan')
            ->get();

        // Revenus par méthode de paiement
        $revenueByPaymentMethod = Subscription::select('payment_method', DB::raw('SUM(price_paid) as total'))
            ->groupBy('payment_method')
            ->get();

        // Taux de renouvellement
        $renewalRate = [
            'total_expired' => Subscription::where('expires_at', '<=', now())->count(),
            'renewed' => Subscription::whereHas('user.subscriptions', function ($q) {
                $q->where('created_at', '>', DB::raw('subscriptions.expires_at'));
            })->count(),
        ];

        // Abonnements actifs par plan
        $activeByPlan = Subscription::select('plan', DB::raw('COUNT(*) as count'))
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->groupBy('plan')
            ->get();

        // Évolution des abonnements
        $subscriptionGrowth = Subscription::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as new_subscriptions')
            )
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('admin.subscriptions.analytics', compact(
            'monthlyRevenue',
            'revenueByPlan',
            'revenueByPaymentMethod',
            'renewalRate',
            'activeByPlan',
            'subscriptionGrowth'
        ));
    }

    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|in:cancel,activate,extend',
            'subscriptions' => 'required|array',
            'subscriptions.*' => 'exists:subscriptions,id',
            'days' => 'required_if:action,extend|integer|min:1',
        ]);

        $count = 0;

        DB::beginTransaction();

        try {
            switch ($validated['action']) {
                case 'cancel':
                    $count = Subscription::whereIn('id', $validated['subscriptions'])
                        ->update(['status' => 'cancelled']);
                    break;

                case 'activate':
                    $count = Subscription::whereIn('id', $validated['subscriptions'])
                        ->update(['status' => 'active']);
                    break;

                case 'extend':
                    foreach (Subscription::whereIn('id', $validated['subscriptions'])->get() as $subscription) {
                        $subscription->extend($validated['days']);
                        $count++;
                    }
                    break;
            }

            DB::commit();

            return back()->with('success', "{$count} abonnements modifiés avec succès.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Une erreur est survenue lors de l\'action groupée.');
        }
    }
}
