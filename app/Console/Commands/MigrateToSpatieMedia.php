<?php

namespace App\Console\Commands;

use App\Models\Property;
use App\Models\PropertyMedia;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class MigrateToSpatieMedia extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'media:migrate-to-spatie 
                            {--dry-run : Exécuter en mode test sans modifications}
                            {--property= : Migrer uniquement une propriété spécifique}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrer les médias de property_media vers Spatie Media Library';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');
        $propertyId = $this->option('property');

        $this->info('🚀 Début de la migration vers Spatie Media Library');
        
        if ($dryRun) {
            $this->warn('⚠️  MODE TEST - Aucune modification ne sera effectuée');
        }

        // Récupérer les propriétés à migrer
        $query = Property::with('propertyMedia');
        
        if ($propertyId) {
            $query->where('id', $propertyId);
        }

        $properties = $query->get();
        $this->info("📊 {$properties->count()} propriétés à traiter");

        $stats = [
            'properties_processed' => 0,
            'images_migrated' => 0,
            'videos_migrated' => 0,
            'errors' => 0,
        ];

        $progressBar = $this->output->createProgressBar($properties->count());
        $progressBar->start();

        foreach ($properties as $property) {
            try {
                $mediaCount = $property->propertyMedia->count();
                
                if ($mediaCount === 0) {
                    $progressBar->advance();
                    continue;
                }

                $this->newLine();
                $this->info("📦 Propriété #{$property->id}: {$property->title} ({$mediaCount} médias)");

                foreach ($property->propertyMedia as $oldMedia) {
                    try {
                        // Nettoyer le chemin
                        $path = str_replace('storage/', '', $oldMedia->path);
                        $fullPath = storage_path('app/public/' . $path);

                        // Vérifier si le fichier existe
                        if (!file_exists($fullPath)) {
                            $this->warn("  ⚠️  Fichier introuvable: {$fullPath}");
                            $stats['errors']++;
                            continue;
                        }

                        if (!$dryRun) {
                            // Ajouter le média via Spatie
                            $collection = $oldMedia->type === 'video' ? 'videos' : 'images';
                            
                            $property->addMedia($fullPath)
                                ->withCustomProperties([
                                    'order' => $oldMedia->order ?? 0,
                                    'is_featured' => $oldMedia->is_featured ?? false,
                                    'migrated_from_id' => $oldMedia->id,
                                ])
                                ->usingName($oldMedia->title ?? 'Media')
                                ->toMediaCollection($collection);

                            $this->line("  ✅ {$oldMedia->type}: {$oldMedia->title}");
                        } else {
                            $this->line("  🔍 [TEST] {$oldMedia->type}: {$oldMedia->title}");
                        }

                        if ($oldMedia->type === 'video') {
                            $stats['videos_migrated']++;
                        } else {
                            $stats['images_migrated']++;
                        }

                    } catch (\Exception $e) {
                        $this->error("  ❌ Erreur: {$e->getMessage()}");
                        $stats['errors']++;
                    }
                }

                $stats['properties_processed']++;
                $progressBar->advance();

            } catch (\Exception $e) {
                $this->error("❌ Erreur pour la propriété #{$property->id}: {$e->getMessage()}");
                $stats['errors']++;
                $progressBar->advance();
            }
        }

        $progressBar->finish();
        $this->newLine(2);

        // Afficher les statistiques
        $this->info('📊 Statistiques de migration:');
        $this->table(
            ['Métrique', 'Valeur'],
            [
                ['Propriétés traitées', $stats['properties_processed']],
                ['Images migrées', $stats['images_migrated']],
                ['Vidéos migrées', $stats['videos_migrated']],
                ['Erreurs', $stats['errors']],
            ]
        );

        if ($dryRun) {
            $this->newLine();
            $this->warn('⚠️  MODE TEST - Pour effectuer la migration réelle, exécutez:');
            $this->line('   php artisan media:migrate-to-spatie');
        } else {
            $this->newLine();
            $this->info('✅ Migration terminée avec succès!');
            $this->newLine();
            $this->warn('⚠️  IMPORTANT: Vérifiez que tout fonctionne correctement avant de supprimer l\'ancienne table property_media');
            $this->line('   Pour supprimer l\'ancienne table: php artisan migrate:rollback --step=1');
        }

        return Command::SUCCESS;
    }
}
