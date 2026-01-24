<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('villas', function (Blueprint $table) {
            if (!Schema::hasColumn('villas', 'availability')) {
                $table->string('availability')->default('all_days')->after('closed_dates');
            }
            if (!Schema::hasColumn('villas', 'available_days')) {
                $table->json('available_days')->nullable()->after('availability');
            }
            if (!Schema::hasColumn('villas', 'available_from')) {
                $table->date('available_from')->nullable()->after('available_days');
            }
            if (!Schema::hasColumn('villas', 'available_to')) {
                $table->date('available_to')->nullable()->after('available_from');
            }
        });
    }

    public function down(): void
    {
        Schema::table('villas', function (Blueprint $table) {
            $table->dropColumn(['availability', 'available_days', 'available_from', 'available_to']);
        });
    }
};
