<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('homepage_settings', function (Blueprint $table) {
            $table->id();
            $table->longText('description')->nullable();
            $table->json('slider_images')->nullable(); // Store array of image paths
            $table->timestamps();
        });

        Schema::create('villa_visibility', function (Blueprint $table) {
            $table->id();
            $table->foreignId('villa_id')->constrained('villas')->onDelete('cascade');
            $table->boolean('is_visible')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('homepage_facilities', function (Blueprint $table) {
            $table->id();
            $table->string('category'); // e.g., 'public_facilities', 'connectivity'
            $table->string('name');
            $table->boolean('is_visible')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('homepage_facilities');
        Schema::dropIfExists('villa_visibility');
        Schema::dropIfExists('homepage_settings');
    }
};
