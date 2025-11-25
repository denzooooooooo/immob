<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('property_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['image', 'video', '360_view']);
            $table->string('path');
            $table->string('title')->nullable();
            $table->string('thumbnail_path')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->string('mime_type')->nullable();
            $table->integer('size')->nullable(); // taille en bytes
            $table->timestamps();

            // Index pour l'ordre et le type
            $table->index(['property_id', 'type', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('property_media');
    }
};
