<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\CacheService;

class CacheWarmupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:warmup 
                            {--force : Force le préchauffage même si le cache existe}
                            {--verbose : Afficher les détails du processus}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Préchauffer le cache avec les données essentielles de l\'application';

    /**
     * Execute the console command.
     */
    public function handle(CacheService $cacheService): int
    {
        $this->info('🔥 Début du préchauffage du cache...');
        
        $startTime = microtime(true);
        
        try {
            // Vider le cache si l'option force est utilisée
            if ($this->option('force')) {
                $this->warn('⚠️  Option --force détectée, vidage du cache existant...');
                $cacheService->flushAll();
            }
            
            // Préchauffer les données principales
            $this->info('📊 Préchauffage des propriétés en vedette...');
            $featuredProperties = $cacheService->getFeaturedProperties();
            $this->option('verbose') && $this->line("   ✓ {$featuredProperties->count()} propriétés en vedette mises en cache");
            
            $this->info('🏠 Préchauffage des propriétés récentes...');
            $recentProperties = $cacheService->getRecentProperties();
            $this->option('verbose') && $this->line("   ✓ {$recentProperties->count()} propriétés récentes mises en cache");
            
            $this->info('🏙️ Préchauffage des villes populaires...');
            $popularCities = $cacheService->getPopularCities();
            $this->option('verbose') && $this->line("   ✓ {$popularCities->count()} villes populaires mises en cache");
            
            $this->info('⚙️ Préchauffage des paramètres du site...');
            $siteSettings = $cacheService->getSiteSettings();
            $this->option('verbose') && $this->line("   ✓ " . count($siteSettings) . " paramètres du site mis en cache");
            
            $this->info('📈 Préchauffage des statistiques générales...');
            $statistics = $cacheService->getGeneralStatistics();
            $this->option('verbose') && $this->line("   ✓ " . count($statistics) . " statistiques mises en cache");
            
            $endTime = microtime(true);
            $duration = round($endTime - $startTime, 2);
            
            $this->newLine();
            $this->info("✅ Préchauffage du cache terminé avec succès en {$duration} secondes");
            
            // Afficher les informations sur le cache
            if ($this->option('verbose')) {
                $cacheInfo = $cacheService->getCacheInfo();
                $this->newLine();
                $this->line('<comment>Informations sur le cache :</comment>');
                $this->line("   Driver: {$cacheInfo['driver']}");
                $this->line("   Store: {$cacheInfo['store_class']}");
                
                if (isset($cacheInfo['redis'])) {
                    $this->line("   Redis version: {$cacheInfo['redis']['version']}");
                    $this->line("   Mémoire utilisée: {$cacheInfo['redis']['used_memory']}");
                }
            }
            
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            $this->error('❌ Erreur lors du préchauffage du cache : ' . $e->getMessage());
            
            if ($this->option('verbose')) {
                $this->line('<error>Stack trace:</error>');
                $this->line($e->getTraceAsString());
            }
            
            return Command::FAILURE;
        }
    }
}
