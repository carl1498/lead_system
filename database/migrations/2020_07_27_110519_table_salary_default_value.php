<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TableSalaryDefaultValue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('salary_income', function($table){
            $table->renameColumn('basic_rate', 'basic');
            $table->decimal('cola', 12, 2)->nullable()->change();
            $table->decimal('acc_allowance', 12, 2)->nullable()->change();
            $table->decimal('adjustments', 12, 2)->nullable()->change();
            $table->decimal('transpo_allowance', 12, 2)->nullable()->change();
            $table->decimal('market_comm', 12, 2)->nullable()->change();
            $table->decimal('jap_comm', 12, 2)->nullable()->change();
            $table->decimal('reg_ot', 12, 3)->nullable()->change();
            $table->decimal('thirteenth', 12, 2)->nullable()->change();
            $table->decimal('leg_hol', 12, 3)->nullable()->change();
            $table->decimal('spcl_hol', 12, 3)->nullable()->change();
            $table->decimal('leg_hol_ot', 12, 3)->nullable()->change();
            $table->decimal('spcl_hol_ot', 12, 3)->nullable()->change();
        });

        Schema::table('salary_deduction', function($table){
            $table->decimal('cash_advance', 12, 2)->nullable()->change();
            $table->decimal('absence', 12, 3)->nullable()->change();
            $table->decimal('late', 12, 3)->nullable()->change();
            $table->decimal('sss', 12, 2)->nullable()->change();
            $table->decimal('phic', 12, 2)->nullable()->change();
            $table->decimal('hdmf', 12, 2)->nullable()->change();
            $table->decimal('others', 12, 2)->nullable()->change();
            $table->decimal('undertime', 12, 3)->nullable()->change();
            $table->decimal('wfh', 12, 2)->nullable()->change();
            $table->decimal('tax', 12, 2)->nullable()->change();
            $table->decimal('man_allocation', 12, 2)->nullable()->change();
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
