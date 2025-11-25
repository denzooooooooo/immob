<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SecurityService;
use App\Services\AnalyticsService;
use App\Models\PropertyView;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class CleanupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cleanup 
                            {--type=all : Type de nettoyage (all, logs, cache, sessions, files, analytics)}
                            {--days=30 : Nombre de jours à conserver}
                            {--force : Forcer le nettoyage sans confirmation}
                            {--dry-run : Simuler le nettoyage sans effectuer les suppressions}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Nettoyer les données obsolètes de l\'application';

    /**
     * Execute the console command.
     */
    public function handle(SecurityService $securityService, AnalyticsService $analyticsService): int
    {
        $type = $this->option('type');
        $days = (int) $this->option('days');
        $force = $this->option('force');
        $dryRun = $this->option('dry-run');
        
        $this->info('🧹 Début du nettoyage de l\'application...');
        
        if ($dryRun) {
            $this->warn('⚠️  Mode simulation activé - aucune suppression ne sera effectuée');
        }
        
        if (!$force && !$dryRun) {
            if (!$this->confirm("Êtes-vous sûr de vouloir nettoyer les données de plus de {$days} jours ?")) {
                $this->info('Nettoyage annulé.');
                return Command::SUCCESS;
            }
        }
        
        $cutoffDate = Carbon::now()->subDays($days);
        $this->info("📅 Date limite : {$cutoffDate->format('Y-m-d H:i:s')}");
        
        $totalCleaned = 0;
        
        try {
            switch ($type) {
                case 'all':
                    $totalCleaned += $this->cleanupLogs($cutoffDate, $dryRun);
                    $totalCleaned += $this->cleanupSessions($cutoffDate, $dryRun);
                    $totalCleaned += $this->cleanupAnalytics($cutoffDate, $dryRun, $analyticsService);
                    $totalCleaned += $this->cleanupSecurity($dryRun, $securityService);
                    $totalCleaned += $this->cleanupFiles($cutoffDate, $dryRun);
                    $totalCleaned += $this->cleanupCache($dryRun);
                    break;
                    
                case 'logs':
                    $totalCleaned += $this->cleanupLogs($cutoffDate, $dryRun);
                    break;
                    
                case 'sessions':
                    $totalCleaned += $this->cleanupSessions($cutoffDate, $dryRun);
                    break;
                    
                case 'analytics':
                    $totalCleaned += $this->cleanupAnalytics($cutoffDate, $dryRun, $analyticsService);
                    break;
                    
                case 'cache':
                    $totalCleaned += $this->cleanupCache($dryRun);
                    break;
                    
                case 'files':
                    $totalCleaned += $this->cleanupFiles($cutoffDate, $dryRun);
                    break;
                    
                default:
                    $this->error("Type de nettoyage non reconnu : {$type}");
                    return Command::FAILURE;
            }
            
            $this->newLine();
            $this->info("✅ Nettoyage terminé avec succès !");
            $this->line("📊 Total d'éléments traités : {$totalCleaned}");
            
            if ($dryRun) {
                $this->warn('⚠️  Aucune suppression effectuée (mode simulation)');
            }
            
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            $this->error('❌ Erreur lors du nettoyage : ' . $e->getMessage());
            Log::error('Erreur lors du nettoyage', [
                'type' => $type,
                'days' => $days,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return Command::FAILURE;
        }
    }

    /**
     * Nettoyer les logs anciens
     */
    private function cleanupLogs(Carbon $cutoffDate, bool $dryRun): int
    {
        $this->info('📝 Nettoyage des logs...');
        
        $logPath = storage_path('logs');
        $cleaned = 0;
        
        if (!is_dir($logPath)) {
            $this->warn('   Répertoire des logs non trouvé');
            return 0;
        }
        
        $files = glob($logPath . '/*.log');
        
        foreach ($files as $file) {
            $fileTime = Carbon::createFromTimestamp(filemtime($file));
            
            if ($fileTime->lt($cutoffDate)) {
                $this->line("   🗑️  Suppression : " . basename($file));
                
                if (!$dryRun) {
                    unlink($file);
                }
                $cleaned++;
            }
        }
        
        $this->line("   ✓ {$cleaned} fichiers de logs traités");
        return $cleaned;
    }

    /**
     * Nettoyer les sessions expirées
     */
    private function cleanupSessions(Carbon $cutoffDate, bool $dryRun): int
    {
        $this->info('🔐 Nettoyage des sessions...');
        
        try {
            $query = DB::table('sessions')
                ->where('last_activity', '<', $cutoffDate->timestamp);
            
            $count = $query->count();
            
            if ($count > 0) {
                $this->line("   🗑️  {$count} sessions expirées trouvées");
                
                if (!$dryRun) {
                    $query->delete();
                }
            }
            
            $this->line("   ✓ {$count} sessions traitées");
            return $count;
            
        } catch (\Exception $e) {
            $this->warn("   ⚠️  Erreur lors du nettoyage des sessions : " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Nettoyer les données d'analytics
     */
    private function cleanupAnalytics(Carbon $cutoffDate, bool $dryRun, AnalyticsService $analyticsService): int
    {
        $this->info('📊 Nettoyage des analytics...');
        
        try {
            $query = PropertyView::where('created_at', '<', $cutoffDate);
            $count = $query->count();
            
            if ($count > 0) {
                $this->line("   🗑️  {$count} vues de propriétés anciennes trouvées");
                
                if (!$dryRun) {
                    $query->delete();
                }
            }
            
            // Nettoyer le cache des analytics
            if (!$dryRun) {
                $analyticsService->invalidateCache();
            }
            
            $this->line("   ✓ {$count} entrées d'analytics traitées");
            return $count;
            
        } catch (\Exception $e) {
            $this->warn("   ⚠️  Erreur lors du nettoyage des analytics : " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Nettoyer les données de sécurité
     */
    private function cleanupSecurity(bool $dryRun, SecurityService $securityService): int
    {
        $this->info('🔒 Nettoyage des données de sécurité...');
        
        try {
            if (!$dryRun) {
                $securityService->cleanupSecurityLogs();
            }
            
            $this->line("   ✓ Données de sécurité nettoyées");
            return 1;
            
        } catch (\Exception $e) {
            $this->warn("   ⚠️  Erreur lors du nettoyage de sécurité : " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Nettoyer les fichiers temporaires et orphelins
     */
    private function cleanupFiles(Carbon $cutoffDate, bool $dryRun): int
    {
        $this->info('📁 Nettoyage des fichiers...');
        
        $cleaned = 0;
        
        // Nettoyer les fichiers temporaires
        $tempPath = storage_path('app/temp');
        if (is_dir($tempPath)) {
            $files = glob($tempPath . '/*');
            
            foreach ($files as $file) {
                if (is_file($file)) {
                    $fileTime = Carbon::createFromTimestamp(filemtime($file));
                    
                    if ($fileTime->lt($cutoffDate)) {
                        $this->line("   🗑️  Suppression fichier temp : " . basename($file));
                        
                        if (!$dryRun) {
                            unlink($file);
                        }
                        $cleaned++;
                    }
                }
            }
        }
        
        // Nettoyer les uploads orphelins (fichiers sans référence en base)
        // TODO: Implémenter la détection des fichiers orphelins
        
        $this->line("   ✓ {$cleaned} fichiers traités");
        return $cleaned;
    }

    /**
     * Nettoyer le cache
     */
    private function cleanupCache(bool $dryRun): int
    {
        $this->info('🗄️ Nettoyage du cache...');
        
        try {
            if (!$dryRun) {
                \Illuminate\Support\Facades\Cache::flush();
            }
            
            $this->line("   ✓ Cache vidé");
            return 1;
            
        } catch (\Exception $e) {
            $this->warn("   ⚠️  Erreur lors du nettoyage du cache : " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Nettoyer les utilisateurs inactifs
     */
    private function cleanupInactiveUsers(Carbon $cutoffDate, bool $dryRun): int
    {
        $this->info('👥 Nettoyage des utilisateurs inactifs...');
        
        try {
            // Utilisateurs qui ne se sont jamais connectés et créés il y a plus de X jours
            $query = User::whereNull('last_login_at')
                ->where('created_at', '<', $cutoffDate)
                ->where('role', 'client'); // Ne pas supprimer les admins/agents
            
            $count = $query->count();
            
            if ($count > 0) {
                $this->line("   🗑️  {$count} utilisateurs inactifs trouvés");
                
                if (!$dryRun) {
                    // Soft delete pour garder une trace
                    $query->delete();
                }
            }
            
            $this->line("   ✓ {$count} utilisateurs inactifs traités");
            return $count;
            
        } catch (\Exception $e) {
            $this->warn("   ⚠️  Erreur lors du nettoyage des utilisateurs : " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Optimiser la base de données
     */
    private function optimizeDatabase(bool $dryRun): int
    {
        $this->info('🗃️ Optimisation de la base de données...');
        
        try {
            if (!$dryRun) {
                // Optimiser les tables principales
                $tables = ['properties', 'users', 'property_views', 'sessions'];
                
                foreach ($tables as $table) {
                    DB::statement("OPTIMIZE TABLE {$table}");
                    $this->line("   ✓ Table {$table} optimisée");
                }
            }
            
            return count($tables ?? []);
            
        } catch (\Exception $e) {
            $this->warn("   ⚠️  Erreur lors de l'optimisation : " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Afficher les statistiques de l'espace disque
     */
    private function showDiskUsage(): void
    {
        $this->info('💾 Utilisation de l\'espace disque :');
        
        $paths = [
            'Storage' => storage_path(),
            'Logs' => storage_path('logs'),
            'Cache' => storage_path('framework/cache'),
            'Sessions' => storage_path('framework/sessions'),
            'Views' => storage_path('framework/views'),
        ];
        
        foreach ($paths as $name => $path) {
            if (is_dir($path)) {
                $size = $this->getDirSize($path);
                $this->line("   {$name}: " . $this->formatBytes($size));
            }
        }
    }

    /**
     * Calculer la taille d'un répertoire
     */
    private function getDirSize(string $directory): int
    {
        $size = 0;
        
        try {
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($directory, \RecursiveDirectoryIterator::SKIP_DOTS)
            );
            
            foreach ($iterator as $file) {
                $size += $file->getSize();
            }
        } catch (\Exception $e) {
            // Ignorer les erreurs d'accès
        }
        
        return $size;
    }

    /**
     * Formater les octets en unités lisibles
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
