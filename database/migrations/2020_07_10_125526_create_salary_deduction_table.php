<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalaryDeductionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salary_deduction', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('sal_mon_id');
            $table->foreign('sal_mon_id')->references('id')->on('salary_monitoring')->onDelete('cascade');
            $table->decimal('cash_advance', 12, 2);
            $table->decimal('absence', 12, 3);
            $table->decimal('late', 12, 3);
            $table->decimal('sss', 12, 2);
            $table->decimal('phic', 12, 2);
            $table->decimal('hdmf', 12, 2);
            $table->decimal('others', 12, 2);
            $table->decimal('undertime', 12, 3);
            $table->decimal('man_allocation', 12, 2);
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
        Schema::dropIfExists('salary_deduction');
    }
}
