<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceBookingScheduleTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    final public function up(): void
    {
        Schema::create('service_booking_schedule_types', function (Blueprint $table) {
            $table->id();
            $table->string('schedule_type_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    final public function down(): void
    {
        Schema::dropIfExists('service_booking_schedule_types');
    }
}
