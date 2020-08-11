<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmpSalaryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emp_salary', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('emp_id');
            $table->foreign('emp_id')->references('id')->on('employees')->onDelete('cascade');
            $table->string('sal_type');
            $table->decimal('rate', 12, 2);
            $table->decimal('daily', 12, 2);
            $table->decimal('cola', 12, 2);
            $table->decimal('acc_allowance', 12, 2);
            $table->decimal('transpo_allowance', 12, 2);
            $table->decimal('sss', 12, 2);
            $table->decimal('phic', 12, 2);
            $table->decimal('hdmf', 12, 2);
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
        Schema::dropIfExists('emp_salary');
    }
}
