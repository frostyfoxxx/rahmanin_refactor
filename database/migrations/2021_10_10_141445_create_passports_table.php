<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePassportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('passports', function (Blueprint $table) {
            $table->id();
            $table->integer('series')->comment('Серия паспорта');
            $table->integer('number')->comment('Номер паспорта');
            $table->date('date_of_issue')->comment('Дата выдачи паспорта');
            $table->string('issued_by')->comment('Кем выдан паспорт');
            $table->date('date_of_birth')->comment('Дата рождения');
            $table->string('gender')->comment('Пол');
            $table->string('place_of_birth')->comment('Место рождения');
            $table->string('registration_address')->comment('Адрес регистрации');
            $table->boolean('lack_of_citizenship')->comment('Не являюсь гражданином РФ');
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
        Schema::dropIfExists('passports');
    }
}
