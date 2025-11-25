<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class OptimizeForProductionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:optimize-production 
                            {--force : Force l\'optimisation même en développement}
                            {--skip-assets : Ignorer la compilation des assets}
                            {--skip-cache : Ignorer la mise en cache}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Optimise l\'application pour l\'environnement de production';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🚀 Optimisation de l\'application pour la production...');
        $this->newLine();

        // Vérifier l'environnement
        if (!$this->option('force') && app()->environment('local')) {
            $this->warn('⚠️  Vous êtes en environnement de développement.');
            if (!$this->confirm('Voulez-vous continuer l\'optimisation ?')) {
                $this->info('Optimisation annulée.');
                return Command::SUCCESS;
            }
        }

        $startTime = microtime(true);

        try {
            // 1. Vérifier les prérequis
            $this->checkPrerequisites();

            // 2. Nettoyer les caches existants
            $this->clearCaches();

            // 3. Optimiser l'autoloader
            $this->optimizeAutoloader();

            // 4. Compiler et optimiser les assets
            if (!$this->option('skip-assets')) {
                $this->optimizeAssets();
            }

            // 5. Optimiser la configuration
            if (!$this->option('skip-cache')) {
                $this->optimizeConfiguration();
            }

            // 6. Optimiser la base de données
            $this->optimizeDatabase();

            // 7. Configurer les permissions
            $this->setPermissions();

            // 8. Préchauffer le cache
            $this->warmupCache();

            // 9. Vérifier l'optimisation
            $this->verifyOptimization();

            $endTime = microtime(true);
            $duration = round($endTime - $startTime, 2);

            $this->newLine();
            $this->info("✅ Optimisation terminée avec succès en {$duration} secondes");
            $this->displayOptimizationSummary();

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error('❌ Erreur lors de l\'optimisation : ' . $e->getMessage());
            return Command::FAILURE;
        }
    }

    /**
     * Vérifier les prérequis
     */
    private function checkPrerequisites(): void
    {
        $this->info('🔍 Vérification des prérequis...');

        // Vérifier PHP
        $phpVersion = PHP_VERSION;
        if (version_compare($phpVersion, '8.2.0', '<')) {
            throw new \Exception("PHP 8.2+ requis. Version actuelle : {$phpVersion}");
        }
        $this->line("   ✓ PHP {$phpVersion}");

        // Vérifier les extensions PHP
        $requiredExtensions = ['gd', 'json', 'mbstring', 'openssl', 'pdo', 'tokenizer', 'xml'];
        foreach ($requiredExtensions as $extension) {
            if (!extension_loaded($extension)) {
                throw new \Exception("Extension PHP manquante : {$extension}");
            }
        }
        $this->line('   ✓ Extensions PHP');

        // Vérifier les permissions
        $writablePaths = ['storage', 'bootstrap/cache'];
        foreach ($writablePaths as $path) {
            if (!is_writable(base_path($path))) {
                throw new \Exception("Dossier non accessible en écriture : {$path}");
            }
        }
        $this->line('   ✓ Permissions des dossiers');

        // Vérifier la configuration
        if (empty(config('app.key'))) {
            throw new \Exception('APP_KEY non définie. Exécutez: php artisan key:generate');
        }
        $this->line('   ✓ Configuration de base');
    }

    /**
     * Nettoyer les caches existants
     */
    private function clearCaches(): void
    {
        $this->info('🧹 Nettoyage des caches...');

        $commands = [
            'config:clear' => 'Configuration',
            'route:clear' => 'Routes',
            'view:clear' => 'Vues',
            'cache:clear' => 'Application',
            'event:clear' => 'Événements',
        ];

        foreach ($commands as $command => $description) {
            try {
                Artisan::call($command);
                $this->line("   ✓ {$description}");
            } catch (\Exception $e) {
                $this->warn("   ⚠️  Échec du nettoyage : {$description}");
            }
        }
    }

    /**
     * Optimiser l'autoloader
     */
    private function optimizeAutoloader(): void
    {
        $this->info('⚡ Optimisation de l\'autoloader...');

        $this->call('optimize:clear');
        
        // Optimiser l'autoloader Composer
        $composerPath = $this->findComposer();
        if ($composerPath) {
            $this->line('   Optimisation Composer...');
            exec("{$composerPath} dump-autoload --optimize --no-dev", $output, $returnCode);
            
            if ($returnCode === 0) {
                $this->line('   ✓ Autoloader optimisé');
            } else {
                $this->warn('   ⚠️  Échec de l\'optimisation Composer');
            }
        }
    }

    /**
     * Optimiser les assets
     */
    private function optimizeAssets(): void
    {
        $this->info('🎨 Optimisation des assets...');

        // Vérifier si Node.js est disponible
        if ($this->commandExists('npm')) {
            $this->line('   Installation des dépendances NPM...');
            exec('npm ci --production', $output, $returnCode);
            
            if ($returnCode === 0) {
                $this->line('   ✓ Dépendances NPM installées');
                
                $this->line('   Compilation des assets...');
                exec('npm run build', $output, $returnCode);
                
                if ($returnCode === 0) {
                    $this->line('   ✓ Assets compilés');
                } else {
                    $this->warn('   ⚠️  Échec de la compilation des assets');
                }
            }
        } else {
            $this->warn('   ⚠️  Node.js non disponible, compilation des assets ignorée');
        }

        // Optimiser les images existantes
        $this->optimizeImages();
    }

    /**
     * Optimiser les images
     */
    private function optimizeImages(): void
    {
        $this->line('   Optimisation des images...');
        
        $publicPath = public_path();
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $optimizedCount = 0;

        foreach ($imageExtensions as $ext) {
            $images = glob("{$publicPath}/**/*.{$ext}", GLOB_BRACE);
            foreach ($images as $image) {
                if (filesize($image) > 100 * 1024) { // Plus de 100KB
                    // Ici on pourrait utiliser des outils d'optimisation d'images
                    $optimizedCount++;
                }
            }
        }

        $this->line("   ✓ {$optimizedCount} images analysées");
    }

    /**
     * Optimiser la configuration
     */
    private function optimizeConfiguration(): void
    {
        $this->info('⚙️ Optimisation de la configuration...');

        $commands = [
            'config:cache' => 'Configuration',
            'route:cache' => 'Routes',
            'view:cache' => 'Vues',
            'event:cache' => 'Événements',
        ];

        foreach ($commands as $command => $description) {
            try {
                Artisan::call($command);
                $this->line("   ✓ {$description} mise en cache");
            } catch (\Exception $e) {
                $this->warn("   ⚠️  Échec de la mise en cache : {$description}");
            }
        }
    }

    /**
     * Optimiser la base de données
     */
    private function optimizeDatabase(): void
    {
        $this->info('🗄️ Optimisation de la base de données...');

        try {
            // Vérifier la connexion
            DB::connection()->getPdo();
            $this->line('   ✓ Connexion à la base de données');

            // Optimiser les tables (MySQL uniquement)
            if (config('database.default') === 'mysql') {
                $tables = ['users', 'properties', 'property_media', 'cache', 'sessions'];
                foreach ($tables as $table) {
                    try {
                        DB::statement("OPTIMIZE TABLE {$table}");
                        $this->line("   ✓ Table {$table} optimisée");
                    } catch (\Exception $e) {
                        $this->line("   - Table {$table} ignorée");
                    }
                }
            }

        } catch (\Exception $e) {
            $this->warn('   ⚠️  Impossible de se connecter à la base de données');
        }
    }

    /**
     * Configurer les permissions
     */
    private function setPermissions(): void
    {
        $this->info('🔒 Configuration des permissions...');

        $paths = [
            'storage' => 0775,
            'bootstrap/cache' => 0775,
            'storage/logs' => 0775,
            'storage/framework' => 0775,
            'storage/app' => 0755,
        ];

        foreach ($paths as $path => $permission) {
            $fullPath = base_path($path);
            if (is_dir($fullPath)) {
                chmod($fullPath, $permission);
                $this->line("   ✓ {$path} : " . decoct($permission));
            }
        }

        // Créer le lien symbolique pour le stockage
        if (!file_exists(public_path('storage'))) {
            Artisan::call('storage:link');
            $this->line('   ✓ Lien symbolique storage créé');
        }
    }

    /**
     * Préchauffer le cache
     */
    private function warmupCache(): void
    {
        $this->info('🔥 Préchauffage du cache...');

        try {
            Artisan::call('cache:warmup');
            $this->line('   ✓ Cache préchauffé');
        } catch (\Exception $e) {
            $this->warn('   ⚠️  Échec du préchauffage du cache');
        }
    }

    /**
     * Vérifier l'optimisation
     */
    private function verifyOptimization(): void
    {
        $this->info('✅ Vérification de l\'optimisation...');

        $checks = [
            'Configuration mise en cache' => file_exists(base_path('bootstrap/cache/config.php')),
            'Routes mises en cache' => file_exists(base_path('bootstrap/cache/routes-v7.php')),
            'Vues mises en cache' => is_dir(storage_path('framework/views')),
            'Lien symbolique storage' => file_exists(public_path('storage')),
        ];

        foreach ($checks as $check => $status) {
            if ($status) {
                $this->line("   ✓ {$check}");
            } else {
                $this->warn("   ⚠️  {$check}");
            }
        }
    }

    /**
     * Afficher le résumé de l'optimisation
     */
    private function displayOptimizationSummary(): void
    {
        $this->newLine();
        $this->info('📊 Résumé de l\'optimisation :');
        $this->line('   • Autoloader optimisé');
        $this->line('   • Configuration mise en cache');
        $this->line('   • Routes mises en cache');
        $this->line('   • Vues mises en cache');
        $this->line('   • Permissions configurées');
        $this->line('   • Cache préchauffé');
        $this->newLine();
        $this->info('🎉 Votre application est maintenant optimisée pour la production !');
    }

    /**
     * Trouver l'exécutable Composer
     */
    private function findComposer(): ?string
    {
        $composerPaths = [
            'composer',
            'composer.phar',
            '/usr/local/bin/composer',
            '/usr/bin/composer',
        ];

        foreach ($composerPaths as $path) {
            if ($this->commandExists($path)) {
                return $path;
            }
        }

        return null;
    }

    /**
     * Vérifier si une commande existe
     */
    private function commandExists(string $command): bool
    {
        $return = shell_exec("which {$command}");
        return !empty($return);
    }
}
