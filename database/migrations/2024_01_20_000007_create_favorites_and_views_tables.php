<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Table des favoris
        Schema::create('favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            // Empêcher les doublons
            $table->unique(['user_id', 'property_id']);
        });

        // Table des vues de propriétés
        Schema::create('property_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('ip_address', 45);
            $table->string('user_agent')->nullable();
            $table->string('session_id')->nullable();
            $table->timestamp('viewed_at');

            // Index pour les statistiques
            $table->index(['property_id', 'viewed_at']);
            $table->index(['ip_address', 'property_id', 'viewed_at']);
        });

        // Table des alertes de recherche
        Schema::create('search_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->json('criteria'); // Critères de recherche (type, prix, ville, etc.)
            $table->enum('frequency', ['daily', 'weekly', 'monthly']);
            $table->timestamp('last_sent_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['user_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('search_alerts');
        Schema::dropIfExists('property_views');
        Schema::dropIfExists('favorites');
    }
};
