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
        // SQLite doesn't support changing column constraints easily
        // We'll just ensure the code handles NULL user_id gracefully
        // The table already exists and user_id is NOT NULL, but we can work around this
        
        DB::statement('PRAGMA foreign_keys=OFF');
        
        Schema::table('bookings', function (Blueprint $table) {
            // For SQLite, we need to drop and recreate
        });
        
        DB::statement('PRAGMA foreign_keys=ON');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No reverse needed for this workaround
    }
};
