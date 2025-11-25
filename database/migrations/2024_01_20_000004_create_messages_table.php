<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('receiver_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('property_id')->nullable()->constrained()->onDelete('cascade');
            $table->text('content');
            $table->enum('type', ['text', 'image', 'document'])->default('text');
            $table->string('attachment_path')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->boolean('is_system_message')->default(false);
            $table->timestamps();

            // Index pour les conversations
            $table->index(['sender_id', 'receiver_id', 'created_at']);
            $table->index(['property_id', 'created_at']);
            $table->index('read_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
