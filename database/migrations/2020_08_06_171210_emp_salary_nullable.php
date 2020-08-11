<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EmpSalaryNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('emp_salary', function (Blueprint $table) {
            $table->decimal('cola', 12, 2)->nullable()->change();
            $table->decimal('acc_allowance', 12, 2)->nullable()->change();
            $table->decimal('transpo_allowance', 12, 2)->nullable()->change();
            $table->decimal('sss', 12, 2)->nullable()->change();
            $table->decimal('phic', 12, 2)->nullable()->change();
            $table->decimal('hdmf', 12, 2)->nullable()->change();
        });
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
