<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;

class SecurityService
{
    /**
     * Durées de blocage en minutes
     */
    const BLOCK_DURATIONS = [
        'login_attempts' => 15,      // 15 minutes après 5 tentatives
        'password_reset' => 60,      // 1 heure après 3 tentatives
        'contact_form' => 30,        // 30 minutes après 5 soumissions
        'api_abuse' => 120,          // 2 heures pour abus API
        'suspicious_activity' => 1440, // 24 heures pour activité suspecte
    ];

    /**
     * Limites de tentatives
     */
    const ATTEMPT_LIMITS = [
        'login' => 5,
        'password_reset' => 3,
        'contact_form' => 5,
        'api_requests' => 100,
        'search_requests' => 50,
    ];

    /**
     * Vérifier et enregistrer une tentative de connexion
     */
    public function checkLoginAttempt(string $email, string $ip): array
    {
        $emailKey = "login_attempts:email:{$email}";
        $ipKey = "login_attempts:ip:{$ip}";
        
        $emailAttempts = Cache::get($emailKey, 0);
        $ipAttempts = Cache::get($ipKey, 0);
        
        // Vérifier si l'email ou l'IP est bloqué
        if ($emailAttempts >= self::ATTEMPT_LIMITS['login']) {
            $this->logSecurityEvent('login_blocked_email', [
                'email' => $email,
                'ip' => $ip,
                'attempts' => $emailAttempts
            ]);
            
            return [
                'allowed' => false,
                'reason' => 'email_blocked',
                'attempts' => $emailAttempts,
                'retry_after' => self::BLOCK_DURATIONS['login_attempts']
            ];
        }
        
        if ($ipAttempts >= self::ATTEMPT_LIMITS['login']) {
            $this->logSecurityEvent('login_blocked_ip', [
                'email' => $email,
                'ip' => $ip,
                'attempts' => $ipAttempts
            ]);
            
            return [
                'allowed' => false,
                'reason' => 'ip_blocked',
                'attempts' => $ipAttempts,
                'retry_after' => self::BLOCK_DURATIONS['login_attempts']
            ];
        }
        
        return [
            'allowed' => true,
            'email_attempts' => $emailAttempts,
            'ip_attempts' => $ipAttempts
        ];
    }

    /**
     * Enregistrer une tentative de connexion échouée
     */
    public function recordFailedLogin(string $email, string $ip): void
    {
        $emailKey = "login_attempts:email:{$email}";
        $ipKey = "login_attempts:ip:{$ip}";
        
        $emailAttempts = Cache::increment($emailKey);
        $ipAttempts = Cache::increment($ipKey);
        
        // Définir l'expiration si c'est la première tentative
        if ($emailAttempts === 1) {
            Cache::put($emailKey, 1, self::BLOCK_DURATIONS['login_attempts']);
        }
        if ($ipAttempts === 1) {
            Cache::put($ipKey, 1, self::BLOCK_DURATIONS['login_attempts']);
        }
        
        $this->logSecurityEvent('failed_login', [
            'email' => $email,
            'ip' => $ip,
            'email_attempts' => $emailAttempts,
            'ip_attempts' => $ipAttempts
        ]);
        
        // Alerter les admins si trop de tentatives
        if ($emailAttempts >= self::ATTEMPT_LIMITS['login'] || $ipAttempts >= self::ATTEMPT_LIMITS['login']) {
            $this->alertAdmins('Tentatives de connexion suspectes', [
                'email' => $email,
                'ip' => $ip,
                'email_attempts' => $emailAttempts,
                'ip_attempts' => $ipAttempts
            ]);
        }
    }

    /**
     * Réinitialiser les tentatives de connexion après succès
     */
    public function resetLoginAttempts(string $email, string $ip): void
    {
        Cache::forget("login_attempts:email:{$email}");
        Cache::forget("login_attempts:ip:{$ip}");
        
        $this->logSecurityEvent('login_success', [
            'email' => $email,
            'ip' => $ip
        ]);
    }

    /**
     * Vérifier la force d'un mot de passe
     */
    public function checkPasswordStrength(string $password): array
    {
        $score = 0;
        $feedback = [];
        
        // Longueur minimum
        if (strlen($password) >= 8) {
            $score += 1;
        } else {
            $feedback[] = 'Le mot de passe doit contenir au moins 8 caractères';
        }
        
        // Contient des minuscules
        if (preg_match('/[a-z]/', $password)) {
            $score += 1;
        } else {
            $feedback[] = 'Ajoutez des lettres minuscules';
        }
        
        // Contient des majuscules
        if (preg_match('/[A-Z]/', $password)) {
            $score += 1;
        } else {
            $feedback[] = 'Ajoutez des lettres majuscules';
        }
        
        // Contient des chiffres
        if (preg_match('/[0-9]/', $password)) {
            $score += 1;
        } else {
            $feedback[] = 'Ajoutez des chiffres';
        }
        
        // Contient des caractères spéciaux
        if (preg_match('/[^a-zA-Z0-9]/', $password)) {
            $score += 1;
        } else {
            $feedback[] = 'Ajoutez des caractères spéciaux (!@#$%^&*)';
        }
        
        // Vérifier contre les mots de passe communs
        if ($this->isCommonPassword($password)) {
            $score = max(0, $score - 2);
            $feedback[] = 'Ce mot de passe est trop commun';
        }
        
        // Déterminer le niveau de sécurité
        $strength = match (true) {
            $score >= 5 => 'très_fort',
            $score >= 4 => 'fort',
            $score >= 3 => 'moyen',
            $score >= 2 => 'faible',
            default => 'très_faible'
        };
        
        return [
            'score' => $score,
            'strength' => $strength,
            'feedback' => $feedback,
            'is_secure' => $score >= 4
        ];
    }

    /**
     * Vérifier si un mot de passe est dans la liste des mots de passe communs
     */
    private function isCommonPassword(string $password): bool
    {
        $commonPasswords = [
            'password', '123456', '123456789', 'qwerty', 'abc123',
            'password123', 'admin', 'letmein', 'welcome', 'monkey',
            'dragon', 'master', 'shadow', 'azerty', 'motdepasse'
        ];
        
        return in_array(strtolower($password), $commonPasswords);
    }

    /**
     * Détecter une activité suspecte
     */
    public function detectSuspiciousActivity(Request $request, User $user = null): bool
    {
        $suspicious = false;
        $reasons = [];
        
        // Vérifier l'User-Agent
        $userAgent = $request->userAgent();
        if (empty($userAgent) || $this->isSuspiciousUserAgent($userAgent)) {
            $suspicious = true;
            $reasons[] = 'user_agent_suspect';
        }
        
        // Vérifier les en-têtes manquants
        if (!$request->hasHeader('Accept') || !$request->hasHeader('Accept-Language')) {
            $suspicious = true;
            $reasons[] = 'headers_manquants';
        }
        
        // Vérifier la fréquence des requêtes
        $ip = $request->ip();
        $requestKey = "requests:ip:{$ip}";
        $requestCount = Cache::increment($requestKey);
        
        if ($requestCount === 1) {
            Cache::put($requestKey, 1, 60); // 1 heure
        }
        
        if ($requestCount > 1000) { // Plus de 1000 requêtes par heure
            $suspicious = true;
            $reasons[] = 'trop_de_requetes';
        }
        
        // Vérifier les changements d'IP fréquents pour un utilisateur connecté
        if ($user) {
            $userIpKey = "user_ips:{$user->id}";
            $userIps = Cache::get($userIpKey, []);
            
            if (!in_array($ip, $userIps)) {
                $userIps[] = $ip;
                Cache::put($userIpKey, array_slice($userIps, -10), 1440); // Garder les 10 dernières IPs
                
                if (count($userIps) > 5) { // Plus de 5 IPs différentes
                    $suspicious = true;
                    $reasons[] = 'changements_ip_frequents';
                }
            }
        }
        
        if ($suspicious) {
            $this->logSecurityEvent('activite_suspecte', [
                'ip' => $ip,
                'user_id' => $user?->id,
                'user_agent' => $userAgent,
                'reasons' => $reasons,
                'url' => $request->fullUrl(),
                'method' => $request->method()
            ]);
            
            // Bloquer temporairement si très suspect
            if (count($reasons) >= 3) {
                $this->blockIp($ip, self::BLOCK_DURATIONS['suspicious_activity']);
            }
        }
        
        return $suspicious;
    }

    /**
     * Vérifier si un User-Agent est suspect
     */
    private function isSuspiciousUserAgent(string $userAgent): bool
    {
        $suspiciousPatterns = [
            '/bot/i',
            '/crawler/i',
            '/spider/i',
            '/scraper/i',
            '/curl/i',
            '/wget/i',
            '/python/i',
            '/java/i',
            '/perl/i',
            '/php/i'
        ];
        
        foreach ($suspiciousPatterns as $pattern) {
            if (preg_match($pattern, $userAgent)) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Bloquer une adresse IP
     */
    public function blockIp(string $ip, int $duration = 60): void
    {
        $blockKey = "blocked_ip:{$ip}";
        Cache::put($blockKey, true, $duration);
        
        $this->logSecurityEvent('ip_blocked', [
            'ip' => $ip,
            'duration' => $duration
        ]);
    }

    /**
     * Vérifier si une IP est bloquée
     */
    public function isIpBlocked(string $ip): bool
    {
        return Cache::has("blocked_ip:{$ip}");
    }

    /**
     * Débloquer une adresse IP
     */
    public function unblockIp(string $ip): void
    {
        Cache::forget("blocked_ip:{$ip}");
        
        $this->logSecurityEvent('ip_unblocked', [
            'ip' => $ip
        ]);
    }

    /**
     * Valider et nettoyer les données d'entrée
     */
    public function sanitizeInput(array $data): array
    {
        $sanitized = [];
        
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                // Supprimer les balises HTML dangereuses
                $value = strip_tags($value, '<p><br><strong><em><ul><ol><li>');
                
                // Échapper les caractères spéciaux
                $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
                
                // Supprimer les caractères de contrôle
                $value = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $value);
                
                // Limiter la longueur
                if (strlen($value) > 10000) {
                    $value = substr($value, 0, 10000);
                }
            }
            
            $sanitized[$key] = $value;
        }
        
        return $sanitized;
    }

    /**
     * Générer un token CSRF sécurisé
     */
    public function generateSecureToken(int $length = 32): string
    {
        return bin2hex(random_bytes($length));
    }

    /**
     * Vérifier l'intégrité d'un fichier uploadé
     */
    public function validateFileUpload($file): array
    {
        $result = [
            'valid' => true,
            'errors' => []
        ];
        
        if (!$file || !$file->isValid()) {
            $result['valid'] = false;
            $result['errors'][] = 'Fichier invalide';
            return $result;
        }
        
        // Vérifier la taille
        $maxSize = 5 * 1024 * 1024; // 5MB
        if ($file->getSize() > $maxSize) {
            $result['valid'] = false;
            $result['errors'][] = 'Fichier trop volumineux (max 5MB)';
        }
        
        // Vérifier l'extension
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'doc', 'docx'];
        $extension = strtolower($file->getClientOriginalExtension());
        
        if (!in_array($extension, $allowedExtensions)) {
            $result['valid'] = false;
            $result['errors'][] = 'Type de fichier non autorisé';
        }
        
        // Vérifier le type MIME
        $allowedMimes = [
            'image/jpeg', 'image/png', 'image/gif', 'image/webp',
            'application/pdf', 'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ];
        
        if (!in_array($file->getMimeType(), $allowedMimes)) {
            $result['valid'] = false;
            $result['errors'][] = 'Type MIME non autorisé';
        }
        
        // Scanner pour les virus (si ClamAV est disponible)
        if ($this->isAntivirusAvailable()) {
            if (!$this->scanForVirus($file->getPathname())) {
                $result['valid'] = false;
                $result['errors'][] = 'Fichier potentiellement dangereux détecté';
            }
        }
        
        return $result;
    }

    /**
     * Vérifier si un antivirus est disponible
     */
    private function isAntivirusAvailable(): bool
    {
        return function_exists('exec') && !empty(shell_exec('which clamscan'));
    }

    /**
     * Scanner un fichier pour les virus
     */
    private function scanForVirus(string $filePath): bool
    {
        try {
            $output = shell_exec("clamscan --no-summary {$filePath}");
            return strpos($output, 'FOUND') === false;
        } catch (\Exception $e) {
            Log::warning('Erreur lors du scan antivirus', [
                'file' => $filePath,
                'error' => $e->getMessage()
            ]);
            return true; // Autoriser si le scan échoue
        }
    }

    /**
     * Logger un événement de sécurité
     */
    private function logSecurityEvent(string $event, array $data): void
    {
        Log::channel('security')->info("Événement de sécurité: {$event}", [
            'event' => $event,
            'data' => $data,
            'timestamp' => now()->toISOString(),
            'user_agent' => request()->userAgent(),
            'ip' => request()->ip(),
        ]);
    }

    /**
     * Alerter les administrateurs
     */
    private function alertAdmins(string $subject, array $data): void
    {
        try {
            $notificationService = app(NotificationService::class);
            $notificationService->notifyAdmins($subject, 'Événement de sécurité détecté', $data);
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'alerte admin', [
                'subject' => $subject,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Obtenir les statistiques de sécurité
     */
    public function getSecurityStats(): array
    {
        // TODO: Implémenter la collecte de statistiques depuis les logs
        return [
            'blocked_ips' => 0,
            'failed_logins_today' => 0,
            'suspicious_activities' => 0,
            'blocked_files' => 0,
        ];
    }

    /**
     * Nettoyer les anciens logs de sécurité
     */
    public function cleanupSecurityLogs(): void
    {
        try {
            // Supprimer les tentatives de connexion expirées
            $patterns = [
                'login_attempts:*',
                'requests:*',
                'user_ips:*',
                'blocked_ip:*'
            ];
            
            // TODO: Implémenter le nettoyage selon le driver de cache
            Log::info('Nettoyage des logs de sécurité effectué');
        } catch (\Exception $e) {
            Log::error('Erreur lors du nettoyage des logs de sécurité', [
                'error' => $e->getMessage()
            ]);
        }
    }
}
