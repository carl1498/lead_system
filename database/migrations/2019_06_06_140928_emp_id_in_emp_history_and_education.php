<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EmpIdInEmpHistoryAndEducation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('prev_employment_history', function (Blueprint $table) {
            $table->unsignedInteger('emp_id')->after('id');
            $table->foreign('emp_id')->references('id')->on('employees');
        });

        Schema::table('educational_background', function (Blueprint $table) {
            $table->unsignedInteger('emp_id')->after('id');
            $table->foreign('emp_id')->references('id')->on('employees');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('emp_history_and_education', function (Blueprint $table) {
            //
        });
    }
}
