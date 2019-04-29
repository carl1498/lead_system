<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentAddHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_add_history', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('stud_id');
            $table->foreign('stud_id')->references('id')->on('students')->onDelete('cascade');
            $table->string('type');
            $table->unsignedInteger('added_by');
            $table->foreign('added_by')->references('id')->on('employees')->onDelete('cascade');
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
        Schema::dropIfExists('student_add_history');
    }
}
