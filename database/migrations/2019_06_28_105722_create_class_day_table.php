<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassDayTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_day', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('class_settings_id');
            $table->foreign('class_settings_id')->references('id')->on('class_settings')->onDelete('cascade');
            $table->unsignedInteger('day_name_id')->nullable();
            $table->foreign('day_name_id')->references('id')->on('day_name');
            $table->unsignedInteger('start_time_id')->nullable();
            $table->foreign('start_time_id')->references('id')->on('time');
            $table->unsignedInteger('end_time_id')->nullable();
            $table->foreign('end_time_id')->references('id')->on('time');
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
        Schema::dropIfExists('class_day');
    }
}
