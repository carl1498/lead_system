<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentEmpHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_emp_history', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('stud_id');
            $table->foreign('stud_id')->references('id')->on('students')->onDelete('cascade');
            $table->string('name');
            $table->string('position');
            $table->string('start');
            $table->string('finished');
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
        Schema::dropIfExists('student_emp_history');
    }
}
