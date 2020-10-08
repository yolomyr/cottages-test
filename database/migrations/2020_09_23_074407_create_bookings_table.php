<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    final public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('service_booking_id')->constrained('service_bookings');
            $table->date('booking_date');
            $table->time('started_at');
            $table->time('finished_at');
            $table->unsignedInteger('people_number')->nullable();
            $table->text('commentary')->nullable();
            $table->boolean('verified')->nullable();
            $table->boolean('deleted_by_admin')->nullable();
            $table->text('cancel_reason')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    final public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
}
