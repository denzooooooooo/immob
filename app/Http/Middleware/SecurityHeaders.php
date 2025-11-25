<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Seulement appliquer les en-têtes de sécurité si activés
        if (!config('app.security_headers_enabled', true)) {
            return $response;
        }

        // Content Security Policy
        $csp = $this->buildContentSecurityPolicy();
        $response->headers->set('Content-Security-Policy', $csp);

        // X-Frame-Options - Empêche l'inclusion dans des iframes
        $response->headers->set('X-Frame-Options', 'DENY');

        // X-Content-Type-Options - Empêche le MIME sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // X-XSS-Protection - Protection XSS pour les anciens navigateurs
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Referrer Policy - Contrôle les informations de référent
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Strict-Transport-Security - Force HTTPS (seulement en production)
        if (app()->environment('production') && $request->secure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }

        // Permissions Policy - Contrôle l'accès aux APIs du navigateur
        $permissionsPolicy = $this->buildPermissionsPolicy();
        $response->headers->set('Permissions-Policy', $permissionsPolicy);

        // Cross-Origin-Embedder-Policy
        $response->headers->set('Cross-Origin-Embedder-Policy', 'require-corp');

        // Cross-Origin-Opener-Policy
        $response->headers->set('Cross-Origin-Opener-Policy', 'same-origin');

        // Cross-Origin-Resource-Policy
        $response->headers->set('Cross-Origin-Resource-Policy', 'same-origin');

        // Cache-Control pour les pages sensibles
        if ($this->isSensitivePage($request)) {
            $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', '0');
        }

        // Supprimer les en-têtes qui révèlent des informations sur le serveur
        $response->headers->remove('Server');
        $response->headers->remove('X-Powered-By');

        return $response;
    }

    /**
     * Construire la politique de sécurité du contenu
     */
    private function buildContentSecurityPolicy(): string
    {
        $policies = [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.tailwindcss.com https://unpkg.com https://cdnjs.cloudflare.com https://www.google.com https://www.gstatic.com",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdnjs.cloudflare.com",
            "font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com",
            "img-src 'self' data: https: blob:",
            "media-src 'self' https:",
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self'",
            "frame-ancestors 'none'",
            "upgrade-insecure-requests",
        ];

        // Ajouter les domaines de paiement en production
        if (app()->environment('production')) {
            $policies[] = "connect-src 'self' https://api.stripe.com https://api.paypal.com https://api.orange.com https://sandbox.momodeveloper.mtn.com https://openapiuat.airtel.africa";
        } else {
            $policies[] = "connect-src 'self' https: wss: ws:";
        }

        return implode('; ', $policies);
    }

    /**
     * Construire la politique des permissions
     */
    private function buildPermissionsPolicy(): string
    {
        $policies = [
            'accelerometer=()',
            'ambient-light-sensor=()',
            'autoplay=()',
            'battery=()',
            'camera=()',
            'cross-origin-isolated=()',
            'display-capture=()',
            'document-domain=()',
            'encrypted-media=()',
            'execution-while-not-rendered=()',
            'execution-while-out-of-viewport=()',
            'fullscreen=(self)',
            'geolocation=(self)',
            'gyroscope=()',
            'keyboard-map=()',
            'magnetometer=()',
            'microphone=()',
            'midi=()',
            'navigation-override=()',
            'payment=(self)',
            'picture-in-picture=()',
            'publickey-credentials-get=()',
            'screen-wake-lock=()',
            'sync-xhr=()',
            'usb=()',
            'web-share=()',
            'xr-spatial-tracking=()',
        ];

        return implode(', ', $policies);
    }

    /**
     * Vérifier si la page est sensible (admin, profil, etc.)
     */
    private function isSensitivePage(Request $request): bool
    {
        $sensitivePaths = [
            'admin',
            'agent',
            'profile',
            'profil',
            'login',
            'register',
            'password',
            'payment',
            'checkout',
        ];

        $path = $request->path();

        foreach ($sensitivePaths as $sensitivePath) {
            if (str_starts_with($path, $sensitivePath)) {
                return true;
            }
        }

        return false;
    }
}
