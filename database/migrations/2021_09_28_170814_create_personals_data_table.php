<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonalsDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personals_data', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->comment('Имя');
            $table->string('middle_name')->comment('Отчество');
            $table->string('last_name')->comment('Фамилия');
            $table->string('phone')->comment('Контактный номер телефона');
            $table->boolean('orphan')->nullable()->comment('Сирота');
            $table->boolean('childhood_disabled')->nullable()->comment('Инвалид детства');
            $table->boolean('the_large_family')->nullable()->comment('Многодетная семья');
            $table->boolean('hostel_for_students')->nullable()->comment('Нуждаюсь в общежитии');
            $table->unsignedBigInteger('users_id')->comment('Идентификатор пользователя');
            $table->foreign('users_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('personals_data');
    }
}
