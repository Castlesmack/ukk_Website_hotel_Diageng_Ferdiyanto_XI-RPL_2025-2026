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
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('booking_id')->nullable();
            $table->unsignedBigInteger('responder_id')->nullable();
            $table->enum('channel', ['web', 'email', 'livechat'])->default('web');
            $table->text('message');
            $table->text('response')->nullable();
            $table->enum('status', ['open', 'answered', 'closed'])->default('open');
            $table->timestamps();

            // Indexes
            $table->index('user_id');
            $table->index('booking_id');
            $table->index('status');

            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('set null');
            $table->foreign('responder_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedbacks');
    }
};
