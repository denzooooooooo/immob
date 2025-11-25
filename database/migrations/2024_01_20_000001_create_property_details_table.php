<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('property_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->integer('year_built')->nullable();
            $table->integer('parking_spaces')->nullable();
            $table->boolean('furnished')->default(false);
            $table->boolean('air_conditioning')->default(false);
            $table->boolean('swimming_pool')->default(false);
            $table->boolean('security_system')->default(false);
            $table->boolean('internet')->default(false);
            $table->boolean('garden')->default(false);
            $table->boolean('balcony')->default(false);
            $table->boolean('elevator')->default(false);
            $table->boolean('garage')->default(false);
            $table->boolean('terrace')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('property_details');
    }
};
