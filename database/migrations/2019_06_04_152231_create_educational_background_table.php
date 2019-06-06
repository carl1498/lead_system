<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEducationalBackgroundTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('educational_background', function (Blueprint $table) {
            $table->increments('id');
            $table->string('school');
            $table->string('start');
            $table->string('end');
            $table->unsignedInteger('course_id')->nullable();
            $table->foreign('course_id')->references('id')->on('courses');
            $table->string('level');
            $table->string('awards')->nullable();
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
        Schema::dropIfExists('educational_background');
    }
}
