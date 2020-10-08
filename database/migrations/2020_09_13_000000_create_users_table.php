<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    final public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('surname', 100);
            $table->date('birthday');
            $table->foreignId('gender_id')->constrained('genders');
            $table->string('phone', 12)->unique();
            $table->string('email')->unique();
            $table->foreignId('user_role_id')->constrained('user_roles');
            $table->string('whatsapp_phone', 12)->nullable();
            $table->string('telegram_phone', 12)->nullable();
            $table->string('viber_phone', 12)->nullable();
            $table->text('work_info')->nullable();
            $table->text('hobby_info')->nullable();
            $table->text('family_info')->nullable();
            $table->text('extra_info')->nullable();
            $table->timestamp('user_verified_at')->nullable();
            $table->string('password');
            $table->unsignedInteger('rating')->default(0);
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
        Schema::dropIfExists('users');
    }
}
