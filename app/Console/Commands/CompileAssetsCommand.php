<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CompileAssetsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assets:compile 
                            {--production : Compile les assets pour la production}
                            {--force : Force la compilation même si les fichiers existent}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Compile les assets pour le déploiement';

    /**
     * Les chemins des assets
     */
    protected $paths = [
        'css' => [
            'source' => 'resources/css/app.css',
            'destination' => 'public/build/assets/app-legacy.css'
        ],
        'js' => [
            'source' => 'resources/js/app.js',
            'destination' => 'public/build/assets/app-legacy.js'
        ],
        'manifest' => 'public/build/manifest.json'
    ];

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🚀 Compilation des assets...');

        // Créer le dossier build s'il n'existe pas
        if (!File::exists('public/build')) {
            File::makeDirectory('public/build/assets', 0755, true);
        }

        try {
            // Compiler CSS
            $this->compileCSS();

            // Compiler JS
            $this->compileJS();

            // Générer le manifest
            $this->generateManifest();

            $this->info('✅ Assets compilés avec succès !');
            
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('❌ Erreur lors de la compilation : ' . $e->getMessage());
            return Command::FAILURE;
        }
    }

    /**
     * Compile le CSS
     */
    protected function compileCSS(): void
    {
        $this->info('Compilation du CSS...');

        if (File::exists($this->paths['css']['source'])) {
            // Copier le fichier CSS source
            File::copy(
                $this->paths['css']['source'],
                $this->paths['css']['destination']
            );

            // Minifier en production
            if ($this->option('production')) {
                $css = File::get($this->paths['css']['destination']);
                $minified = $this->minifyCSS($css);
                File::put($this->paths['css']['destination'], $minified);
            }

            $this->line('   ✓ CSS compilé');
        } else {
            $this->warn('   ⚠️  Fichier CSS source non trouvé');
        }
    }

    /**
     * Compile le JavaScript
     */
    protected function compileJS(): void
    {
        $this->info('Compilation du JavaScript...');

        if (File::exists($this->paths['js']['source'])) {
            // Copier le fichier JS source
            File::copy(
                $this->paths['js']['source'],
                $this->paths['js']['destination']
            );

            // Minifier en production
            if ($this->option('production')) {
                $js = File::get($this->paths['js']['destination']);
                $minified = $this->minifyJS($js);
                File::put($this->paths['js']['destination'], $minified);
            }

            $this->line('   ✓ JavaScript compilé');
        } else {
            $this->warn('   ⚠️  Fichier JavaScript source non trouvé');
        }
    }

    /**
     * Génère le manifest.json
     */
    protected function generateManifest(): void
    {
        $this->info('Génération du manifest...');

        $manifest = [
            'resources/css/app.css' => [
                'file' => 'assets/app-legacy.css',
                'src' => 'resources/css/app.css',
                'isEntry' => true
            ],
            'resources/js/app.js' => [
                'file' => 'assets/app-legacy.js',
                'src' => 'resources/js/app.js',
                'isEntry' => true,
                'css' => ['assets/app-legacy.css']
            ]
        ];

        File::put(
            $this->paths['manifest'],
            json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );

        $this->line('   ✓ Manifest généré');
    }

    /**
     * Minifie le CSS
     */
    protected function minifyCSS(string $css): string
    {
        // Supprimer les commentaires
        $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
        
        // Supprimer les espaces après les : ; { }
        $css = preg_replace('/\s*([:;{}])\s*/', '$1', $css);
        
        // Supprimer les espaces inutiles
        $css = preg_replace('/\s+/', ' ', $css);
        
        // Supprimer les points-virgules inutiles
        $css = str_replace(';}', '}', $css);
        
        return trim($css);
    }

    /**
     * Minifie le JavaScript
     */
    protected function minifyJS(string $js): string
    {
        if (class_exists('JShrink\Minifier')) {
            return \JShrink\Minifier::minify($js);
        }

        // Minification basique si JShrink n'est pas disponible
        $js = preg_replace('/(?:(?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)|(?:(?<!\:|\\\|\'|\")\/\/.*))/', '', $js);
        $js = preg_replace('/\s+/', ' ', $js);
        $js = preg_replace('/\s*([,:;{}()])\s*/', '$1', $js);
        
        return trim($js);
    }
}
