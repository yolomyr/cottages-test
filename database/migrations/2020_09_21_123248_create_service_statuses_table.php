<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    final public function up(): void
    {
        Schema::create('service_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('status_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    final public function down(): void
    {
        Schema::dropIfExists('service_statuses');
    }
}
