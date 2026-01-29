<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Menambahkan index ke tabel bookings untuk optimasi query performa
     * terutama untuk kalender ketersediaan dan pencarian booking
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Index untuk query filter by villa_id dan status (sering digunakan)
            $table->index(['villa_id', 'status']);
            $table->index(['check_in_date']);
            $table->index(['check_out_date']);
            $table->index(['status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Drop indexes - nama index di-generate otomatis oleh Laravel
            $table->dropIndex(['villa_id', 'status']);
            $table->dropIndex(['check_in_date']);
            $table->dropIndex(['check_out_date']);
            $table->dropIndex(['status']);
        });
    }
};
