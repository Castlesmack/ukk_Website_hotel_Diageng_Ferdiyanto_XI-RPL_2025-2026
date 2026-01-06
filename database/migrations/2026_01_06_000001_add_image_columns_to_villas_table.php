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
        Schema::table('villas', function (Blueprint $table) {
            if (!Schema::hasColumn('villas', 'thumbnail_path')) {
                $table->string('thumbnail_path')->nullable()->after('description')->comment('Path to villa thumbnail image');
            }
            if (!Schema::hasColumn('villas', 'images')) {
                $table->json('images')->nullable()->after('thumbnail_path')->comment('JSON array of gallery image paths');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('villas', function (Blueprint $table) {
            $table->dropColumn(['thumbnail_path', 'images']);
        });
    }
};
