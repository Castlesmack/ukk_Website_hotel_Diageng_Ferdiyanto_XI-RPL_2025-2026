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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('villa_id');
            $table->unsignedBigInteger('villa_room_type_id');
            $table->string('booking_code')->unique();
            $table->date('check_in_date');
            $table->date('check_out_date');
            $table->integer('nights')->nullable();
            $table->integer('rooms_booked')->default(1);
            $table->integer('guests')->default(1);
            
            // Guest information fields
            $table->string('guest_name')->nullable();
            $table->string('guest_email')->nullable();
            $table->string('guest_phone')->nullable();
            $table->integer('guest_count')->nullable();
            $table->text('special_requests')->nullable();
            
            $table->decimal('total_price', 12, 2)->default(0);
            $table->string('status')->default('pending');
            $table->boolean('is_verified')->default(false);
            $table->unsignedBigInteger('verified_by')->nullable();
            $table->dateTime('verified_at')->nullable();
            $table->string('payment_status')->default('unpaid');
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('villa_id')->references('id')->on('villas')->onDelete('cascade');
            $table->foreign('villa_room_type_id')->references('id')->on('villa_room_types')->onDelete('cascade');
            $table->foreign('verified_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
