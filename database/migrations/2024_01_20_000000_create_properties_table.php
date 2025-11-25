<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('slug')->unique();
            $table->enum('type', ['apartment', 'house', 'villa', 'studio', 'office', 'shop', 'land', 'warehouse', 'commercial']);
            $table->enum('status', ['for_sale', 'for_rent', 'hotel']);
            $table->decimal('price', 12, 2);
            $table->enum('currency', ['XAF', 'EUR', 'USD'])->default('XAF');
            $table->text('description');
            $table->integer('bedrooms')->nullable();
            $table->integer('bathrooms')->nullable();
            $table->decimal('surface_area', 10, 2);
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('address');
            $table->string('city');
            $table->string('neighborhood');
            $table->boolean('featured')->default(false);
            $table->boolean('published')->default(false);
            $table->integer('views_count')->default(0);
            $table->timestamps();
            
            // Index pour la recherche
            $table->index(['type', 'status', 'city']);
            $table->index('price');
            $table->index('surface_area');
            $table->index(['latitude', 'longitude']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
