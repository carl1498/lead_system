<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class StudentSignupMonthNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE students MODIFY date_of_signup DATE');
        Schema::table('students', function (Blueprint $table){
            $table->dropForeign('students_departure_month_id_foreign');
        });
        DB::statement('ALTER TABLE students MODIFY departure_month_id INT(10) UNSIGNED NULL');
        DB::statement('ALTER TABLE students ADD CONSTRAINT students_departure_month_id_foreign
            FOREIGN KEY (departure_month_id) REFERENCES departure_months (id)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
