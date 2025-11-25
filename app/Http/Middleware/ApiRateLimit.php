<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ApiRateLimit
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, $maxAttempts = 60, $decayMinutes = 1): Response
    {
        $key = $this->resolveRequestSignature($request);
        $maxAttempts = $this->resolveMaxAttempts($request, $maxAttempts);
        
        if ($this->tooManyAttempts($key, $maxAttempts)) {
            return $this->buildResponse($key, $maxAttempts);
        }

        $this->hit($key, $decayMinutes * 60);

        $response = $next($request);

        return $this->addHeaders(
            $response,
            $maxAttempts,
            $this->calculateRemainingAttempts($key, $maxAttempts)
        );
    }

    /**
     * Resolve request signature.
     */
    protected function resolveRequestSignature(Request $request): string
    {
        if (Auth::check()) {
            return 'api_rate_limit:user:' . Auth::id();
        }

        return 'api_rate_limit:ip:' . $request->ip();
    }

    /**
     * Resolve the number of attempts if the user is authenticated.
     */
    protected function resolveMaxAttempts(Request $request, $maxAttempts): int
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Différentes limites selon le rôle
            switch ($user->role) {
                case 'admin':
                    return $maxAttempts * 10; // Admins ont 10x plus de requêtes
                case 'agent':
                    return $maxAttempts * 5;  // Agents ont 5x plus de requêtes
                default:
                    return $maxAttempts * 2;  // Utilisateurs connectés ont 2x plus
            }
        }

        return $maxAttempts;
    }

    /**
     * Determine if the given key has been "accessed" too many times.
     */
    protected function tooManyAttempts(string $key, int $maxAttempts): bool
    {
        return Cache::get($key, 0) >= $maxAttempts;
    }

    /**
     * Increment the counter for a given key for a given decay time.
     */
    protected function hit(string $key, int $decaySeconds = 60): int
    {
        $current = Cache::get($key, 0);
        
        if ($current === 0) {
            Cache::put($key, 1, $decaySeconds);
            return 1;
        }
        
        return Cache::increment($key);
    }

    /**
     * Calculate the number of remaining attempts.
     */
    protected function calculateRemainingAttempts(string $key, int $maxAttempts): int
    {
        return max(0, $maxAttempts - Cache::get($key, 0));
    }

    /**
     * Create a 'too many attempts' response.
     */
    protected function buildResponse(string $key, int $maxAttempts): Response
    {
        $retryAfter = Cache::get($key . ':timer', 60);
        
        return response()->json([
            'success' => false,
            'message' => 'Too many requests. Please try again later.',
            'error' => 'Rate limit exceeded',
            'retry_after' => $retryAfter,
            'max_attempts' => $maxAttempts
        ], 429)->header('Retry-After', $retryAfter);
    }

    /**
     * Add the limit header information to the given response.
     */
    protected function addHeaders(Response $response, int $maxAttempts, int $remainingAttempts): Response
    {
        return $response->withHeaders([
            'X-RateLimit-Limit' => $maxAttempts,
            'X-RateLimit-Remaining' => $remainingAttempts,
            'X-RateLimit-Reset' => now()->addMinute()->timestamp,
        ]);
    }
}
