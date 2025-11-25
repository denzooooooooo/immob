<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('plan', ['basic', 'premium', 'pro', 'annual']);
            $table->decimal('price_paid', 10, 2);
            $table->enum('currency', ['XAF', 'EUR', 'USD'])->default('XAF');
            $table->timestamp('starts_at');
            $table->timestamp('expires_at');
            $table->enum('status', ['active', 'expired', 'cancelled', 'pending']);
            $table->enum('payment_method', ['airtel_money', 'orange_money', 'card', 'bank_transfer']);
            $table->string('transaction_id')->nullable();
            $table->json('payment_details')->nullable(); // Détails du paiement
            $table->integer('properties_limit'); // Limite d'annonces pour ce plan
            $table->integer('properties_used')->default(0); // Annonces utilisées
            $table->boolean('featured_listings')->default(false); // Annonces mises en avant
            $table->boolean('priority_support')->default(false); // Support prioritaire
            $table->timestamps();

            // Index pour les requêtes fréquentes
            $table->index(['user_id', 'status']);
            $table->index(['expires_at', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
