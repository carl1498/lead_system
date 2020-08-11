<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalaryIncomeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salary_income', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('sal_mon_id');
            $table->foreign('sal_mon_id')->references('id')->on('salary_monitoring')->onDelete('cascade');
            $table->decimal('basic_rate', 12, 3);
            $table->decimal('cola', 12, 2);
            $table->decimal('acc_allowance', 12, 2);
            $table->decimal('adjustments', 12, 3);
            $table->decimal('transpo_allowance', 12, 3);
            $table->decimal('market_comm', 12, 2);
            $table->decimal('jap_comm', 12, 2);
            $table->decimal('reg_ot', 12, 3);
            $table->decimal('thirteenth', 12, 2);
            $table->decimal('leg_hol', 12, 3);
            $table->decimal('spcl_hol', 12, 3);
            $table->decimal('leg_hol_ot', 12, 3);
            $table->decimal('spcl_hol_ot', 12, 3);
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
        Schema::dropIfExists('salary_income');
    }
}
