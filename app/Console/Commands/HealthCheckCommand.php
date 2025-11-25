<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use App\Services\CacheService;
use App\Services\SecurityService;
use App\Models\User;
use App\Models\Property;
use Carbon\Carbon;

class HealthCheckCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:health-check 
                            {--format=table : Format de sortie (table, json, summary)}
                            {--check=all : Type de vérification (all, database, cache, storage, security, services)}
                            {--fix : Tenter de corriger automatiquement les problèmes détectés}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Vérifier l\'état de santé de l\'application et de ses composants';

    /**
     * Résultats des vérifications
     */
    private array $results = [];

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $format = $this->option('format');
        $check = $this->option('check');
        $fix = $this->option('fix');
        
        $this->info('🏥 Vérification de l\'état de santé de l\'application...');
        $this->newLine();
        
        $startTime = microtime(true);
        
        try {
            switch ($check) {
                case 'all':
                    $this->checkDatabase();
                    $this->checkCache();
                    $this->checkStorage();
                    $this->checkSecurity();
                    $this->checkServices();
                    $this->checkConfiguration();
                    $this->checkPerformance();
                    break;
                    
                case 'database':
                    $this->checkDatabase();
                    break;
                    
                case 'cache':
                    $this->checkCache();
                    break;
                    
                case 'storage':
                    $this->checkStorage();
                    break;
                    
                case 'security':
                    $this->checkSecurity();
                    break;
                    
                case 'services':
                    $this->checkServices();
                    break;
                    
                default:
                    $this->error("Type de vérification non reconnu : {$check}");
                    return Command::FAILURE;
            }
            
            $endTime = microtime(true);
            $duration = round($endTime - $startTime, 2);
            
            // Afficher les résultats
            $this->displayResults($format);
            
            // Tenter les corrections si demandé
            if ($fix) {
                $this->attemptFixes();
            }
            
            $this->newLine();
            $this->info("✅ Vérification terminée en {$duration} secondes");
            
            // Déterminer le code de sortie
            $hasErrors = collect($this->results)->contains('status', 'error');
            $hasWarnings = collect($this->results)->contains('status', 'warning');
            
            if ($hasErrors) {
                $this->error('❌ Des erreurs critiques ont été détectées');
                return Command::FAILURE;
            } elseif ($hasWarnings) {
                $this->warn('⚠️  Des avertissements ont été détectés');
                return 1; // Code de sortie personnalisé pour les warnings
            } else {
                $this->info('✅ Tous les systèmes sont opérationnels');
                return Command::SUCCESS;
            }
            
        } catch (\Exception $e) {
            $this->error('❌ Erreur lors de la vérification : ' . $e->getMessage());
            return Command::FAILURE;
        }
    }

    /**
     * Vérifier la base de données
     */
    private function checkDatabase(): void
    {
        $this->info('🗄️ Vérification de la base de données...');
        
        try {
            // Test de connexion
            $start = microtime(true);
            DB::connection()->getPdo();
            $connectionTime = round((microtime(true) - $start) * 1000, 2);
            
            $this->addResult('database_connection', 'success', 'Connexion réussie', [
                'time' => $connectionTime . 'ms'
            ]);
            
            // Test de requête simple
            $start = microtime(true);
            $userCount = User::count();
            $queryTime = round((microtime(true) - $start) * 1000, 2);
            
            $this->addResult('database_query', 'success', 'Requêtes fonctionnelles', [
                'users_count' => $userCount,
                'query_time' => $queryTime . 'ms'
            ]);
            
            // Vérifier l'espace disque de la base
            $dbSize = $this->getDatabaseSize();
            $status = $dbSize > 1000 ? 'warning' : 'success';
            
            $this->addResult('database_size', $status, 'Taille de la base de données', [
                'size' => $this->formatBytes($dbSize * 1024 * 1024)
            ]);
            
            // Vérifier les tables principales
            $tables = ['users', 'properties', 'cities', 'property_media'];
            foreach ($tables as $table) {
                try {
                    $count = DB::table($table)->count();
                    $this->addResult("table_{$table}", 'success', "Table {$table}", [
                        'records' => $count
                    ]);
                } catch (\Exception $e) {
                    $this->addResult("table_{$table}", 'error', "Table {$table} inaccessible", [
                        'error' => $e->getMessage()
                    ]);
                }
            }
            
        } catch (\Exception $e) {
            $this->addResult('database_connection', 'error', 'Connexion échouée', [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Vérifier le cache
     */
    private function checkCache(): void
    {
        $this->info('🗄️ Vérification du cache...');
        
        try {
            // Test d'écriture/lecture
            $testKey = 'health_check_' . time();
            $testValue = 'test_value_' . rand(1000, 9999);
            
            $start = microtime(true);
            Cache::put($testKey, $testValue, 60);
            $writeTime = round((microtime(true) - $start) * 1000, 2);
            
            $start = microtime(true);
            $retrievedValue = Cache::get($testKey);
            $readTime = round((microtime(true) - $start) * 1000, 2);
            
            if ($retrievedValue === $testValue) {
                $this->addResult('cache_operations', 'success', 'Opérations de cache', [
                    'write_time' => $writeTime . 'ms',
                    'read_time' => $readTime . 'ms'
                ]);
            } else {
                $this->addResult('cache_operations', 'error', 'Échec des opérations de cache');
            }
            
            // Nettoyer le test
            Cache::forget($testKey);
            
            // Vérifier les informations du cache
            $cacheService = app(CacheService::class);
            $cacheInfo = $cacheService->getCacheInfo();
            
            $this->addResult('cache_driver', 'success', 'Driver de cache', [
                'driver' => $cacheInfo['driver'],
                'store' => class_basename($cacheInfo['store_class'])
            ]);
            
        } catch (\Exception $e) {
            $this->addResult('cache_operations', 'error', 'Erreur de cache', [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Vérifier le stockage
     */
    private function checkStorage(): void
    {
        $this->info('💾 Vérification du stockage...');
        
        // Vérifier les disques configurés
        $disks = ['local', 'public'];
        
        foreach ($disks as $disk) {
            try {
                $storage = Storage::disk($disk);
                
                // Test d'écriture
                $testFile = 'health_check_' . time() . '.txt';
                $testContent = 'Health check test file';
                
                $start = microtime(true);
                $storage->put($testFile, $testContent);
                $writeTime = round((microtime(true) - $start) * 1000, 2);
                
                // Test de lecture
                $start = microtime(true);
                $content = $storage->get($testFile);
                $readTime = round((microtime(true) - $start) * 1000, 2);
                
                // Test de suppression
                $storage->delete($testFile);
                
                if ($content === $testContent) {
                    $this->addResult("storage_{$disk}", 'success', "Stockage {$disk}", [
                        'write_time' => $writeTime . 'ms',
                        'read_time' => $readTime . 'ms'
                    ]);
                } else {
                    $this->addResult("storage_{$disk}", 'error', "Échec du stockage {$disk}");
                }
                
            } catch (\Exception $e) {
                $this->addResult("storage_{$disk}", 'error', "Erreur stockage {$disk}", [
                    'error' => $e->getMessage()
                ]);
            }
        }
        
        // Vérifier l'espace disque
        $this->checkDiskSpace();
    }

    /**
     * Vérifier la sécurité
     */
    private function checkSecurity(): void
    {
        $this->info('🔒 Vérification de la sécurité...');
        
        // Vérifier la configuration de sécurité
        $securityChecks = [
            'app_debug' => [
                'value' => config('app.debug'),
                'expected' => false,
                'message' => 'Mode debug'
            ],
            'app_key' => [
                'value' => !empty(config('app.key')),
                'expected' => true,
                'message' => 'Clé d\'application'
            ],
            'https' => [
                'value' => request()->secure() || app()->environment('local'),
                'expected' => true,
                'message' => 'HTTPS activé'
            ],
        ];
        
        foreach ($securityChecks as $key => $check) {
            $status = $check['value'] === $check['expected'] ? 'success' : 'warning';
            $this->addResult("security_{$key}", $status, $check['message'], [
                'current' => $check['value'],
                'expected' => $check['expected']
            ]);
        }
        
        // Vérifier les permissions des fichiers
        $this->checkFilePermissions();
    }

    /**
     * Vérifier les services externes
     */
    private function checkServices(): void
    {
        $this->info('🌐 Vérification des services externes...');
        
        // Vérifier les services de paiement (en mode test)
        $this->checkPaymentServices();
        
        // Vérifier les services de géolocalisation
        $this->checkGeocodingServices();
        
        // Vérifier les services de notification
        $this->checkNotificationServices();
    }

    /**
     * Vérifier la configuration
     */
    private function checkConfiguration(): void
    {
        $this->info('⚙️ Vérification de la configuration...');
        
        $requiredConfigs = [
            'app.name' => 'Nom de l\'application',
            'app.url' => 'URL de l\'application',
            'mail.from.address' => 'Adresse email par défaut',
            'database.default' => 'Base de données par défaut',
        ];
        
        foreach ($requiredConfigs as $key => $description) {
            $value = config($key);
            $status = !empty($value) ? 'success' : 'warning';
            
            $this->addResult("config_{$key}", $status, $description, [
                'value' => $value ?: 'Non configuré'
            ]);
        }
    }

    /**
     * Vérifier les performances
     */
    private function checkPerformance(): void
    {
        $this->info('⚡ Vérification des performances...');
        
        // Temps de réponse de l'application
        $start = microtime(true);
        Property::limit(10)->get();
        $queryTime = round((microtime(true) - $start) * 1000, 2);
        
        $status = $queryTime > 1000 ? 'warning' : 'success';
        $this->addResult('performance_query', $status, 'Temps de requête', [
            'time' => $queryTime . 'ms'
        ]);
        
        // Utilisation mémoire
        $memoryUsage = memory_get_usage(true);
        $memoryPeak = memory_get_peak_usage(true);
        
        $this->addResult('performance_memory', 'success', 'Utilisation mémoire', [
            'current' => $this->formatBytes($memoryUsage),
            'peak' => $this->formatBytes($memoryPeak)
        ]);
    }

    /**
     * Vérifier l'espace disque
     */
    private function checkDiskSpace(): void
    {
        $path = storage_path();
        $freeBytes = disk_free_space($path);
        $totalBytes = disk_total_space($path);
        $usedPercent = round((($totalBytes - $freeBytes) / $totalBytes) * 100, 2);
        
        $status = $usedPercent > 90 ? 'error' : ($usedPercent > 80 ? 'warning' : 'success');
        
        $this->addResult('disk_space', $status, 'Espace disque', [
            'free' => $this->formatBytes($freeBytes),
            'total' => $this->formatBytes($totalBytes),
            'used_percent' => $usedPercent . '%'
        ]);
    }

    /**
     * Vérifier les permissions des fichiers
     */
    private function checkFilePermissions(): void
    {
        $paths = [
            storage_path() => 'Storage',
            storage_path('logs') => 'Logs',
            storage_path('framework/cache') => 'Cache',
            storage_path('framework/sessions') => 'Sessions',
        ];
        
        foreach ($paths as $path => $name) {
            if (is_dir($path)) {
                $writable = is_writable($path);
                $status = $writable ? 'success' : 'error';
                
                $this->addResult("permissions_{$name}", $status, "Permissions {$name}", [
                    'path' => $path,
                    'writable' => $writable
                ]);
            }
        }
    }

    /**
     * Vérifier les services de paiement
     */
    private function checkPaymentServices(): void
    {
        // Stripe
        if (config('services.stripe.key')) {
            $this->addResult('service_stripe', 'success', 'Stripe configuré');
        } else {
            $this->addResult('service_stripe', 'warning', 'Stripe non configuré');
        }
        
        // PayPal
        if (config('services.paypal.sandbox.client_id')) {
            $this->addResult('service_paypal', 'success', 'PayPal configuré');
        } else {
            $this->addResult('service_paypal', 'warning', 'PayPal non configuré');
        }
    }

    /**
     * Vérifier les services de géolocalisation
     */
    private function checkGeocodingServices(): void
    {
        if (config('services.google_maps.api_key')) {
            $this->addResult('service_google_maps', 'success', 'Google Maps configuré');
        } else {
            $this->addResult('service_google_maps', 'warning', 'Google Maps non configuré');
        }
    }

    /**
     * Vérifier les services de notification
     */
    private function checkNotificationServices(): void
    {
        if (config('services.slack.webhook_url')) {
            $this->addResult('service_slack', 'success', 'Slack configuré');
        } else {
            $this->addResult('service_slack', 'info', 'Slack non configuré');
        }
    }

    /**
     * Obtenir la taille de la base de données
     */
    private function getDatabaseSize(): float
    {
        try {
            $result = DB::select("
                SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size_mb
                FROM information_schema.tables
                WHERE table_schema = ?
            ", [config('database.connections.mysql.database')]);
            
            return $result[0]->size_mb ?? 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Ajouter un résultat de vérification
     */
    private function addResult(string $check, string $status, string $message, array $details = []): void
    {
        $this->results[] = [
            'check' => $check,
            'status' => $status,
            'message' => $message,
            'details' => $details,
            'timestamp' => Carbon::now()->toISOString()
        ];
    }

    /**
     * Afficher les résultats
     */
    private function displayResults(string $format): void
    {
        $this->newLine();
        
        switch ($format) {
            case 'json':
                $this->line(json_encode($this->results, JSON_PRETTY_PRINT));
                break;
                
            case 'summary':
                $this->displaySummary();
                break;
                
            default:
                $this->displayTable();
                break;
        }
    }

    /**
     * Afficher un résumé
     */
    private function displaySummary(): void
    {
        $total = count($this->results);
        $success = collect($this->results)->where('status', 'success')->count();
        $warnings = collect($this->results)->where('status', 'warning')->count();
        $errors = collect($this->results)->where('status', 'error')->count();
        
        $this->info("📊 Résumé des vérifications :");
        $this->line("   Total : {$total}");
        $this->line("   ✅ Succès : {$success}");
        $this->line("   ⚠️  Avertissements : {$warnings}");
        $this->line("   ❌ Erreurs : {$errors}");
    }

    /**
     * Afficher un tableau
     */
    private function displayTable(): void
    {
        $headers = ['Vérification', 'Statut', 'Message', 'Détails'];
        $rows = [];
        
        foreach ($this->results as $result) {
            $status = match ($result['status']) {
                'success' => '<fg=green>✅ Succès</>',
                'warning' => '<fg=yellow>⚠️  Avertissement</>',
                'error' => '<fg=red>❌ Erreur</>',
                default => '<fg=blue>ℹ️  Info</>'
            };
            
            $details = '';
            if (!empty($result['details'])) {
                $details = collect($result['details'])
                    ->map(fn($value, $key) => "{$key}: {$value}")
                    ->implode(', ');
            }
            
            $rows[] = [
                $result['check'],
                $status,
                $result['message'],
                $details
            ];
        }
        
        $this->table($headers, $rows);
    }

    /**
     * Tenter de corriger les problèmes
     */
    private function attemptFixes(): void
    {
        $this->newLine();
        $this->info('🔧 Tentative de correction des problèmes...');
        
        $fixableIssues = collect($this->results)->where('status', 'error');
        
        if ($fixableIssues->isEmpty()) {
            $this->line('   Aucun problème à corriger');
            return;
        }
        
        foreach ($fixableIssues as $issue) {
            $this->attemptFix($issue);
        }
    }

    /**
     * Tenter de corriger un problème spécifique
     */
    private function attemptFix(array $issue): void
    {
        switch ($issue['check']) {
            case 'cache_operations':
                $this->line('   🔧 Tentative de vidage du cache...');
                try {
                    Cache::flush();
                    $this->line('   ✅ Cache vidé avec succès');
                } catch (\Exception $e) {
                    $this->line('   ❌ Échec du vidage du cache');
                }
                break;
                
            default:
                $this->line("   ⚠️  Aucune correction automatique disponible pour : {$issue['check']}");
                break;
        }
    }

    /**
     * Formater les octets
     */
    private function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
