<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\SecurityService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Socialite\Facades\Socialite;
use Carbon\Carbon;

class AuthApiController extends Controller
{
    protected $securityService;
    protected $notificationService;

    public function __construct(SecurityService $securityService, NotificationService $notificationService)
    {
        $this->securityService = $securityService;
        $this->notificationService = $notificationService;
    }

    /**
     * Connexion utilisateur
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:8',
            'device_name' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Vérifier les tentatives de connexion suspectes
        if ($this->securityService->detectSuspiciousLogin($request, $request->email)) {
            return response()->json([
                'success' => false,
                'message' => 'Too many login attempts. Please try again later.',
                'error' => 'rate_limit_exceeded'
            ], 429);
        }

        // Tentative de connexion
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials',
                'error' => 'invalid_credentials'
            ], 401);
        }

        $user = Auth::user();

        // Vérifier si l'email est vérifié
        if (!$user->email_verified_at) {
            return response()->json([
                'success' => false,
                'message' => 'Email not verified',
                'error' => 'email_not_verified'
            ], 403);
        }

        // Vérifier si le compte est actif
        if (!$user->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Account is suspended',
                'error' => 'account_suspended'
            ], 403);
        }

        // Créer le token d'API
        $deviceName = $request->device_name ?? $request->userAgent();
        $token = $user->createToken($deviceName)->plainTextToken;

        // Mettre à jour les informations de connexion
        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip()
        ]);

        // Analyser les patterns de navigation
        $navigationCheck = $this->securityService->analyzeNavigationPatterns($request, $user->id);
        if ($navigationCheck !== 'clean') {
            // Log l'événement mais permettre la connexion
            logger()->warning("Suspicious navigation pattern detected for user {$user->id}: {$navigationCheck}");
        }

        return response()->json([
            'success' => true,
            'message' => 'Successfully logged in',
            'data' => [
                'token' => $token,
                'token_type' => 'Bearer',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'avatar' => $user->avatar_url,
                    'is_verified' => (bool) $user->email_verified_at,
                    'created_at' => $user->created_at->toISOString()
                ]
            ]
        ]);
    }

    /**
     * Inscription utilisateur
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:user,agent',
            'phone' => 'nullable|string|max:20',
            'accept_terms' => 'required|accepted'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Vérifier la force du mot de passe
        $passwordCheck = $this->securityService->validatePasswordStrength($request->password);
        if ($passwordCheck['score'] < 60) {
            return response()->json([
                'success' => false,
                'message' => 'Password is too weak',
                'errors' => [
                    'password' => $passwordCheck['feedback']
                ]
            ], 422);
        }

        // Détecter les activités frauduleuses
        $fraudCheck = $this->securityService->detectFraudulentActivity(null, 'account_creation', $request->all());
        if ($fraudCheck === 'high_risk') {
            return response()->json([
                'success' => false,
                'message' => 'Registration blocked due to suspicious activity',
                'error' => 'suspicious_activity'
            ], 403);
        }

        try {
            // Créer l'utilisateur
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'phone' => $request->phone,
                'registration_ip' => $request->ip(),
                'is_active' => true
            ]);

            // Envoyer l'email de vérification
            $user->sendEmailVerificationNotification();

            // Créer le token d'API
            $token = $user->createToken($request->userAgent())->plainTextToken;

            // Envoyer l'email de bienvenue
            $this->notificationService->sendWelcomeEmail($user);

            return response()->json([
                'success' => true,
                'message' => 'Successfully registered',
                'data' => [
                    'token' => $token,
                    'token_type' => 'Bearer',
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'role' => $user->role,
                        'created_at' => $user->created_at->toISOString()
                    ]
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Registration failed',
                'error' => config('app.debug') ? $e->getMessage() : 'Server error'
            ], 500);
        }
    }

    /**
     * Déconnexion
     */
    public function logout(Request $request)
    {
        try {
            // Révoquer le token actuel
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Successfully logged out'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Logout failed',
                'error' => config('app.debug') ? $e->getMessage() : 'Server error'
            ], 500);
        }
    }

    /**
     * Obtenir le profil utilisateur
     */
    public function me(Request $request)
    {
        try {
            $user = $request->user();
            
            // Vérifier l'intégrité des données
            $integrityCheck = $this->securityService->validateUserDataIntegrity($user);
            if (!empty($integrityCheck)) {
                logger()->warning("Data integrity issues detected for user {$user->id}", $integrityCheck);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'role' => $user->role,
                        'avatar' => $user->avatar_url,
                        'phone' => $user->phone,
                        'is_verified' => (bool) $user->email_verified_at,
                        'created_at' => $user->created_at->toISOString(),
                        'last_login_at' => $user->last_login_at ? Carbon::parse($user->last_login_at)->toISOString() : null,
                        'subscription' => $user->role === 'agent' ? $user->subscription : null,
                        'notifications_enabled' => $user->notifications_enabled,
                        'preferences' => $user->preferences
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch user profile',
                'error' => config('app.debug') ? $e->getMessage() : 'Server error'
            ], 500);
        }
    }

    /**
     * Mettre à jour le profil
     */
    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|nullable|string|max:20',
            'current_password' => 'required_with:new_password|string',
            'new_password' => 'sometimes|string|min:8|confirmed',
            'notifications_enabled' => 'sometimes|boolean',
            'preferences' => 'sometimes|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = $request->user();
            $updates = [];

            // Vérifier le mot de passe actuel si fourni
            if ($request->has('current_password')) {
                if (!Hash::check($request->current_password, $user->password)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Current password is incorrect',
                        'error' => 'invalid_password'
                    ], 403);
                }

                if ($request->has('new_password')) {
                    // Vérifier la force du nouveau mot de passe
                    $passwordCheck = $this->securityService->validatePasswordStrength($request->new_password);
                    if ($passwordCheck['score'] < 60) {
                        return response()->json([
                            'success' => false,
                            'message' => 'New password is too weak',
                            'errors' => [
                                'new_password' => $passwordCheck['feedback']
                            ]
                        ], 422);
                    }

                    $updates['password'] = Hash::make($request->new_password);
                }
            }

            // Mettre à jour les champs de base
            if ($request->has('name')) $updates['name'] = $request->name;
            if ($request->has('phone')) $updates['phone'] = $request->phone;
            if ($request->has('notifications_enabled')) {
                $updates['notifications_enabled'] = $request->notifications_enabled;
            }
            if ($request->has('preferences')) {
                $updates['preferences'] = array_merge($user->preferences ?? [], $request->preferences);
            }

            // Appliquer les mises à jour
            $user->update($updates);

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'phone' => $user->phone,
                        'notifications_enabled' => $user->notifications_enabled,
                        'preferences' => $user->preferences,
                        'updated_at' => $user->updated_at->toISOString()
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update profile',
                'error' => config('app.debug') ? $e->getMessage() : 'Server error'
            ], 500);
        }
    }

    /**
     * Mot de passe oublié
     */
    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $status = Password::sendResetLink(
                $request->only('email')
            );

            if ($status === Password::RESET_LINK_SENT) {
                return response()->json([
                    'success' => true,
                    'message' => 'Password reset link sent'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to send reset link',
                'error' => __($status)
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to process request',
                'error' => config('app.debug') ? $e->getMessage() : 'Server error'
            ], 500);
        }
    }

    /**
     * Réinitialiser le mot de passe
     */
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Vérifier la force du mot de passe
        $passwordCheck = $this->securityService->validatePasswordStrength($request->password);
        if ($passwordCheck['score'] < 60) {
            return response()->json([
                'success' => false,
                'message' => 'Password is too weak',
                'errors' => [
                    'password' => $passwordCheck['feedback']
                ]
            ], 422);
        }

        try {
            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user, $password) {
                    $user->update([
                        'password' => Hash::make($password),
                        'remember_token' => null
                    ]);
                }
            );

            if ($status === Password::PASSWORD_RESET) {
                return response()->json([
                    'success' => true,
                    'message' => 'Password reset successfully'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to reset password',
                'error' => __($status)
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to process request',
                'error' => config('app.debug') ? $e->getMessage() : 'Server error'
            ], 500);
        }
    }

    /**
     * Vérifier l'email
     */
    public function verifyEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
            'hash' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = User::findOrFail($request->id);

            if (!hash_equals(sha1($user->getEmailForVerification()), $request->hash)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid verification link',
                    'error' => 'invalid_hash'
                ], 400);
            }

            if ($user->hasVerifiedEmail()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Email already verified'
                ]);
            }

            $user->markEmailAsVerified();

            return response()->json([
                'success' => true,
                'message' => 'Email verified successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to verify email',
                'error' => config('app.debug') ? $e->getMessage() : 'Server error'
            ], 500);
        }
    }

    /**
     * Renvoyer l'email de vérification
     */
    public function resendVerification(Request $request)
    {
        try {
            $user = $request->user();

            if ($user->hasVerifiedEmail()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email already verified',
                    'error' => 'already_verified'
                ], 400);
            }

            $user->sendEmailVerificationNotification();

            return response()->json([
                'success' => true,
                'message' => 'Verification email sent'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send verification email',
                'error' => config('app.debug') ? $e->getMessage() : 'Server error'
            ], 500);
        }
    }

    /**
     * Supprimer le compte
     */
    public function deleteAccount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = $request->user();

            if (!Hash::check($request->password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid password',
                    'error' => 'invalid_password'
                ], 403);
            }

            // Révoquer tous les tokens
            $user->tokens()->delete();

            // Supprimer le compte (soft delete)
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'Account deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete account',
                'error' => config('app.debug') ? $e->getMessage() : 'Server error'
            ], 500);
        }
    }

    /**
     * Authentification sociale
     */
    public function redirectToProvider($provider)
    {
        try {
            return Socialite::driver($provider)->stateless()->redirect();
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to initiate social login',
                'error' => config('app.debug') ? $e->getMessage() : 'Server error'
            ], 500);
        }
    }

    /**
     * Callback d'authentification sociale
     */
    public function handleProviderCallback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->stateless()->user();

            $user = User::where('email', $socialUser->getEmail())->first();

            if (!$user) {
                // Créer un nouveau compte
                $user = User::create([
                    'name' => $socialUser->getName(),
                    'email' => $socialUser->getEmail(),
                    'password' => Hash::make(str_random(16)),
                    'role' => 'user',
                    'email_verified_at' => now(),
                    'provider' => $provider,
                    'provider_id' => $socialUser->getId(),
                    'avatar' => $socialUser->getAvatar()
                ]);
            } else {
                // Mettre à jour les informations du provider
                $user->update([
                    'provider' => $provider,
                    'provider_id' => $socialUser->getId(),
                    'avatar' => $socialUser->getAvatar()
                ]);
            }

            // Créer le token
            $token = $user->createToken('social-auth')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Successfully authenticated',
                'data' => [
                    'token' => $token,
                    'token_type' => 'Bearer',
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'role' => $user->role,
                        'avatar' => $user->avatar,
                        'created_at' => $user->created_at->toISOString()
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to authenticate with ' . $provider,
                'error' => config('app.debug') ? $e->getMessage() : 'Server error'
            ], 500);
        }
    }
}
