<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->increments('id');
            $table->string('fname');
            $table->string('mname')->nullable();
            $table->string('lname');
            $table->date('birthdate');
            $table->integer('age');
            $table->string('contact');
            $table->unsignedInteger('program_id')->nullable();
            $table->foreign('program_id')->references('id')->on('programs')->onDelete('cascade');
            $table->unsignedInteger('school_id')->nullable();
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            $table->unsignedInteger('benefactor_id')->nullable();
            $table->foreign('benefactor_id')->references('id')->on('benefactors')->onDelete('cascade');
            $table->string('address');
            $table->string('email');
            $table->unsignedInteger('referral_id');
            $table->foreign('referral_id')->references('id')->on('employees');
            $table->date('date_of_signup');
            $table->date('date_of_medical')->nullable();
            $table->date('date_of_completion')->nullable();
            $table->enum('gender', ['Male', 'Female']);
            $table->unsignedInteger('branch_id');
            $table->foreign('branch_id')->references('id')->on('branches');
            $table->string('course')->nullable();
            $table->unsignedInteger('departure_year_id');
            $table->foreign('departure_year_id')->references('id')->on('departure_years');
            $table->unsignedInteger('departure_month_id');
            $table->foreign('departure_month_id')->references('id')->on('departure_months');
            $table->enum('status', ['Active', 'Back Out', 'Final School']);
            $table->string('remarks')->nullable();
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
        Schema::dropIfExists('students');
    }
}
