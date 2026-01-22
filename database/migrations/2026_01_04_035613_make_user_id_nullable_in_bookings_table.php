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
        // Only run if the bookings table exists
        if (Schema::hasTable('bookings')) {
            Schema::table('bookings', function (Blueprint $table) {
                // Check if column exists and is not already nullable
                if (Schema::hasColumn('bookings', 'user_id')) {
                    $table->unsignedBigInteger('user_id')->nullable()->change();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No reverse needed for this workaround
    }
};
