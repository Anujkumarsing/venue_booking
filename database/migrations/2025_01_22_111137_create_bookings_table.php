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
            $table->integer('user_id');
            $table->integer('admin_id');
            $table->string('client_1')->nullable();
            $table->string('client_2')->nullable();
            $table->string('email')->nullable();
            $table->string('phone', 64)->nullable();
            $table->integer('guest_count')->default('0');
            $table->text('notes')->nullable();
            $table->string('file')->nullable();
            $table->integer('booking_status')->comment('0-awaiting approval/1-approved/2-rejected/3-edit awaiting approval/4-cancelled');
            $table->integer('calendar_id');
            $table->timestamps();
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
