<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EmployeeFamilyMnameNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE employee_child MODIFY mname varchar(191) NULL');
        DB::statement('ALTER TABLE employee_spouse MODIFY mname varchar(191) NULL');
        DB::statement('ALTER TABLE employee_emergency MODIFY mname varchar(191) NULL');
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
