<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceBookingSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    final public function up(): void
    {
        Schema::create('service_booking_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_booking_id')->constrained('service_bookings');
            $table->foreignId('schedule_type_id')->constrained('service_booking_schedule_types');
            $table->time('started_at');
            $table->time('finished_at');
            $table->integer('week_day')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    final public function down(): void
    {
        Schema::dropIfExists('service_booking_schedules');
    }
}
