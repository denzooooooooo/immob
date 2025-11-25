<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Property;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Intervention\Image\Facades\Image;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::withCount(['properties', 'subscriptions'])
            ->with(['subscriptions' => function($query) {
                $query->where('status', 'active')
                    ->where('expires_at', '>', now())
                    ->latest();
            }]);

        // Filtres
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('subscription')) {
            if ($request->subscription === 'active') {
                $query->whereHas('subscriptions', function ($q) {
                    $q->where('status', 'active')
                        ->where('expires_at', '>', now());
                });
            } elseif ($request->subscription === 'expired') {
                $query->whereDoesntHave('subscriptions', function ($q) {
                    $q->where('status', 'active')
                        ->where('expires_at', '>', now());
                });
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Tri
        $sort = $request->sort ?? 'created_at';
        $direction = $request->direction ?? 'desc';
        $query->orderBy($sort, $direction);

        $users = $query->paginate(20)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => 'required|in:admin,agent,client',
            'phone' => 'nullable|string|max:20',
            'status' => 'required|in:active,inactive,suspended',
            'company_name' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'website' => 'nullable|url|max:255',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'email_notifications' => 'boolean',
            'sms_notifications' => 'boolean',
        ]);

        DB::beginTransaction();

        try {
            // Traiter l'avatar si fourni
            $avatarPath = null;
            if ($request->hasFile('avatar')) {
                $avatar = $request->file('avatar');
                $avatarPath = 'avatars/' . time() . '.' . $avatar->getClientOriginalExtension();
                
                // Redimensionner et sauvegarder l'avatar
                Image::make($avatar)
                    ->fit(300, 300)
                    ->save(storage_path('app/public/' . $avatarPath));
            }

            // Créer l'utilisateur
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
                'phone' => $validated['phone'],
                'status' => $validated['status'],
                'company_name' => $validated['company_name'],
                'bio' => $validated['bio'],
                'website' => $validated['website'],
                'address' => $validated['address'],
                'city' => $validated['city'],
                'avatar' => $avatarPath,
                'email_notifications' => $validated['email_notifications'] ?? true,
                'sms_notifications' => $validated['sms_notifications'] ?? false,
            ]);

            DB::commit();

            return redirect()
                ->route('admin.users.show', $user)
                ->with('success', 'Utilisateur créé avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            if (isset($avatarPath)) {
                Storage::delete('public/' . $avatarPath);
            }
            return back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la création de l\'utilisateur.');
        }
    }

    public function show(User $user)
    {
        $user->load([
            'properties' => function ($query) {
                $query->latest()->limit(5);
            },
            'subscriptions' => function ($query) {
                $query->latest()->limit(5);
            },
            'favorites' => function ($query) {
                $query->latest()->limit(5);
            },
        ]);

        // Statistiques
        $stats = [
            'total_properties' => $user->properties()->count(),
            'active_properties' => $user->properties()->where('published', true)->count(),
            'total_views' => DB::table('property_views')
                ->whereIn('property_id', $user->properties()->pluck('id'))
                ->count(),
            'total_favorites' => DB::table('favorites')
                ->whereIn('property_id', $user->properties()->pluck('id'))
                ->count(),
            'total_spent' => $user->subscriptions()->sum('price_paid'),
        ];

        return view('admin.users.show', compact('user', 'stats'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'role' => 'required|in:admin,agent,client',
            'phone' => 'nullable|string|max:20',
            'status' => 'required|in:active,inactive,suspended',
            'company_name' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'website' => 'nullable|url|max:255',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'email_notifications' => 'boolean',
            'sms_notifications' => 'boolean',
            'remove_avatar' => 'boolean',
        ]);

        DB::beginTransaction();

        try {
            $data = collect($validated)->except(['password', 'avatar', 'remove_avatar'])->toArray();

            // Gérer le mot de passe si fourni
            if ($validated['password']) {
                $data['password'] = Hash::make($validated['password']);
            }

            // Gérer l'avatar
            if ($request->boolean('remove_avatar')) {
                if ($user->avatar) {
                    Storage::delete('public/' . $user->avatar);
                }
                $data['avatar'] = null;
            } elseif ($request->hasFile('avatar')) {
                // Supprimer l'ancien avatar
                if ($user->avatar) {
                    Storage::delete('public/' . $user->avatar);
                }

                // Sauvegarder le nouvel avatar
                $avatar = $request->file('avatar');
                $avatarPath = 'avatars/' . time() . '.' . $avatar->getClientOriginalExtension();
                
                Image::make($avatar)
                    ->fit(300, 300)
                    ->save(storage_path('app/public/' . $avatarPath));

                $data['avatar'] = $avatarPath;
            }

            // Mettre à jour l'utilisateur
            $user->update($data);

            DB::commit();

            return redirect()
                ->route('admin.users.show', $user)
                ->with('success', 'Utilisateur mis à jour avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la mise à jour de l\'utilisateur.');
        }
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        DB::beginTransaction();

        try {
            // Supprimer l'avatar
            if ($user->avatar) {
                Storage::delete('public/' . $user->avatar);
            }

            // Supprimer les médias des propriétés
            foreach ($user->properties as $property) {
                foreach ($property->media as $media) {
                    Storage::delete('public/' . $media->path);
                    if ($media->thumbnail_path) {
                        Storage::delete('public/' . $media->thumbnail_path);
                    }
                }
            }

            // Supprimer l'utilisateur (les relations seront supprimées automatiquement)
            $user->delete();

            DB::commit();

            return redirect()
                ->route('admin.users.index')
                ->with('success', 'Utilisateur supprimé avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Une erreur est survenue lors de la suppression de l\'utilisateur.');
        }
    }

    public function impersonate(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas vous connecter en tant que vous-même.');
        }

        // Stocker l'ID de l'administrateur actuel
        session()->put('admin_id', auth()->id());

        // Se connecter en tant que l'utilisateur
        auth()->login($user);

        return redirect()->route('dashboard')
            ->with('success', 'Vous êtes maintenant connecté en tant que ' . $user->name);
    }

    public function stopImpersonating()
    {
        if (!session()->has('admin_id')) {
            return back()->with('error', 'Vous n\'êtes pas en train d\'usurper l\'identité d\'un utilisateur.');
        }

        // Récupérer l'administrateur
        $admin = User::find(session()->get('admin_id'));

        // Supprimer l'ID de l'administrateur de la session
        session()->forget('admin_id');

        // Se reconnecter en tant qu'administrateur
        auth()->login($admin);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Vous êtes de retour en tant qu\'administrateur.');
    }

    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|in:delete,activate,deactivate,suspend,ban',
            'ids' => 'required|array',
            'ids.*' => 'exists:users,id',
        ]);

        // Empêcher la suppression de son propre compte
        if (in_array(auth()->id(), $validated['ids'])) {
            return back()->with('error', 'Vous ne pouvez pas effectuer cette action sur votre propre compte.');
        }

        $count = 0;

        DB::beginTransaction();

        try {
            switch ($validated['action']) {
                case 'delete':
                    foreach (User::whereIn('id', $validated['ids'])->get() as $user) {
                        // Supprimer l'avatar
                        if ($user->avatar) {
                            Storage::delete('public/' . $user->avatar);
                        }

                        // Supprimer les médias des propriétés
                        foreach ($user->properties as $property) {
                            foreach ($property->media as $media) {
                                Storage::delete('public/' . $media->path);
                                if ($media->thumbnail_path) {
                                    Storage::delete('public/' . $media->thumbnail_path);
                                }
                            }
                        }

                        $user->delete();
                        $count++;
                    }
                    break;

                case 'activate':
                    $count = User::whereIn('id', $validated['ids'])
                        ->update(['status' => 'active']);
                    break;

                case 'deactivate':
                    $count = User::whereIn('id', $validated['ids'])
                        ->update(['status' => 'inactive']);
                    break;

                case 'suspend':
                    $count = User::whereIn('id', $validated['ids'])
                        ->update(['status' => 'suspended']);
                    break;

                case 'ban':
                    $count = User::whereIn('id', $validated['ids'])
                        ->update(['status' => 'banned']);
                    break;
            }

            DB::commit();

            return back()->with('success', "{$count} utilisateurs modifiés avec succès.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Une erreur est survenue lors de l\'action groupée.');
        }
    }

    public function activate(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas modifier votre propre statut.');
        }

        $user->update(['status' => 'active']);

        return back()->with('success', 'Utilisateur activé avec succès.');
    }

    public function deactivate(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas modifier votre propre statut.');
        }

        $user->update(['status' => 'inactive']);

        return back()->with('success', 'Utilisateur désactivé avec succès.');
    }
}
