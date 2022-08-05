<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchoolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->string('school_name')->comment('Наименование школы');
            $table->integer('number_of_classes')->comment('Количество оконченных классов');
            $table->integer('year_of_ending')->comment('Год окончания');
            $table->bigInteger('number_of_certificate')->comment('Номер аттестата');
            $table->integer('number_of_photo')->nullable()->comment('Количество фотографий');
            $table->string('version_of_the_certificate')->nullable()->comment(
                'Версия сертификата: Оригинал или копия'
            );
            $table->float('middlemark')->nullable()->comment('Средний балл');
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
        Schema::dropIfExists('schools');
    }
}
