<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            // Log critical errors to external services
            if ($this->shouldReport($e)) {
                $this->logToExternalService($e);
            }
        });

        // Handle API exceptions
        $this->renderable(function (Throwable $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return $this->handleApiException($e, $request);
            }
        });
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $e)
    {
        // Handle specific exceptions for web requests
        if (!$request->expectsJson()) {
            return $this->handleWebException($e, $request);
        }

        return parent::render($request, $e);
    }

    /**
     * Handle web exceptions with custom error pages
     */
    protected function handleWebException(Throwable $e, Request $request)
    {
        // 404 - Not Found
        if ($e instanceof NotFoundHttpException || $e instanceof ModelNotFoundException) {
            return response()->view('errors.404', [
                'exception' => $e,
                'request' => $request
            ], 404);
        }

        // 403 - Forbidden
        if ($e instanceof HttpException && $e->getStatusCode() === 403) {
            return response()->view('errors.403', [
                'exception' => $e,
                'request' => $request
            ], 403);
        }

        // 429 - Too Many Requests
        if ($e instanceof ThrottleRequestsException) {
            return response()->view('errors.429', [
                'exception' => $e,
                'request' => $request,
                'retryAfter' => $e->getHeaders()['Retry-After'] ?? 60
            ], 429);
        }

        // 500 - Internal Server Error
        if ($e instanceof HttpException && $e->getStatusCode() === 500) {
            return response()->view('errors.500', [
                'exception' => $e,
                'request' => $request
            ], 500);
        }

        // 503 - Service Unavailable
        if ($e instanceof HttpException && $e->getStatusCode() === 503) {
            return response()->view('errors.503', [
                'exception' => $e,
                'request' => $request,
                'estimatedMinutes' => 30
            ], 503);
        }

        return parent::render($request, $e);
    }

    /**
     * Handle API exceptions with JSON responses
     */
    protected function handleApiException(Throwable $e, Request $request)
    {
        $status = 500;
        $message = 'Une erreur inattendue s\'est produite.';
        $errors = null;

        // Validation errors
        if ($e instanceof ValidationException) {
            $status = 422;
            $message = 'Les données fournies ne sont pas valides.';
            $errors = $e->errors();
        }
        // Authentication errors
        elseif ($e instanceof AuthenticationException) {
            $status = 401;
            $message = 'Authentification requise.';
        }
        // Not found errors
        elseif ($e instanceof NotFoundHttpException || $e instanceof ModelNotFoundException) {
            $status = 404;
            $message = 'Ressource non trouvée.';
        }
        // Method not allowed
        elseif ($e instanceof MethodNotAllowedHttpException) {
            $status = 405;
            $message = 'Méthode non autorisée.';
        }
        // Rate limiting
        elseif ($e instanceof ThrottleRequestsException) {
            $status = 429;
            $message = 'Trop de requêtes. Veuillez réessayer plus tard.';
        }
        // HTTP exceptions
        elseif ($e instanceof HttpException) {
            $status = $e->getStatusCode();
            $message = $e->getMessage() ?: $this->getDefaultMessage($status);
        }

        $response = [
            'success' => false,
            'message' => $message,
            'status' => $status,
        ];

        if ($errors) {
            $response['errors'] = $errors;
        }

        // Add debug information in development
        if (config('app.debug') && !app()->environment('production')) {
            $response['debug'] = [
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ];
        }

        return response()->json($response, $status);
    }

    /**
     * Convert an authentication exception into a response.
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Authentification requise.',
                'status' => 401
            ], 401);
        }

        return redirect()->guest(route('login'))->with('error', 'Vous devez être connecté pour accéder à cette page.');
    }

    /**
     * Get default message for HTTP status codes
     */
    protected function getDefaultMessage(int $status): string
    {
        return match ($status) {
            400 => 'Requête invalide.',
            401 => 'Non autorisé.',
            403 => 'Accès interdit.',
            404 => 'Ressource non trouvée.',
            405 => 'Méthode non autorisée.',
            422 => 'Données non valides.',
            429 => 'Trop de requêtes.',
            500 => 'Erreur interne du serveur.',
            502 => 'Passerelle incorrecte.',
            503 => 'Service indisponible.',
            504 => 'Délai d\'attente de la passerelle dépassé.',
            default => 'Une erreur s\'est produite.',
        };
    }

    /**
     * Log errors to external monitoring services
     */
    protected function logToExternalService(Throwable $e): void
    {
        try {
            // Log to Sentry if configured
            if (config('sentry.dsn')) {
                app('sentry')->captureException($e);
            }

            // Log critical errors to Slack if configured
            if (config('services.slack.webhook_url') && $this->isCriticalError($e)) {
                $this->notifySlack($e);
            }

            // Custom logging for specific error types
            if ($e instanceof \PDOException) {
                Log::critical('Database connection error', [
                    'message' => $e->getMessage(),
                    'code' => $e->getCode(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ]);
            }

        } catch (\Exception $loggingException) {
            // Don't let logging errors break the application
            Log::error('Failed to log exception to external service', [
                'original_exception' => $e->getMessage(),
                'logging_exception' => $loggingException->getMessage(),
            ]);
        }
    }

    /**
     * Determine if an error is critical
     */
    protected function isCriticalError(Throwable $e): bool
    {
        return $e instanceof \PDOException ||
               $e instanceof \ErrorException ||
               ($e instanceof HttpException && $e->getStatusCode() >= 500);
    }

    /**
     * Send notification to Slack
     */
    protected function notifySlack(Throwable $e): void
    {
        $webhookUrl = config('services.slack.webhook_url');
        
        if (!$webhookUrl) {
            return;
        }

        $payload = [
            'text' => '🚨 Erreur critique sur ' . config('app.name'),
            'attachments' => [
                [
                    'color' => 'danger',
                    'fields' => [
                        [
                            'title' => 'Exception',
                            'value' => get_class($e),
                            'short' => true,
                        ],
                        [
                            'title' => 'Message',
                            'value' => $e->getMessage(),
                            'short' => false,
                        ],
                        [
                            'title' => 'Fichier',
                            'value' => $e->getFile() . ':' . $e->getLine(),
                            'short' => true,
                        ],
                        [
                            'title' => 'URL',
                            'value' => request()->fullUrl(),
                            'short' => true,
                        ],
                        [
                            'title' => 'Utilisateur',
                            'value' => Auth::check() ? Auth::user()->email : 'Invité',
                            'short' => true,
                        ],
                        [
                            'title' => 'Environnement',
                            'value' => config('app.env'),
                            'short' => true,
                        ],
                    ],
                    'ts' => time(),
                ],
            ],
        ];

        try {
            $ch = curl_init($webhookUrl);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_exec($ch);
            curl_close($ch);
        } catch (\Exception $e) {
            // Silently fail if Slack notification fails
        }
    }

    /**
     * Determine if the exception should be reported
     */
    public function shouldReport(Throwable $e): bool
    {
        // Don't report certain exceptions in development
        if (app()->environment('local')) {
            $dontReport = [
                NotFoundHttpException::class,
                ValidationException::class,
                AuthenticationException::class,
            ];

            foreach ($dontReport as $type) {
                if ($e instanceof $type) {
                    return false;
                }
            }
        }

        return parent::shouldReport($e);
    }
}
