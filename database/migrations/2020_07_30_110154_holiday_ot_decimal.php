<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class HolidayOtDecimal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('salary_income', function (Blueprint $table) {
            $table->decimal('reg_ot', 12, 1)->nullable()->change();
            $table->decimal('leg_hol', 12, 1)->nullable()->change();
            $table->decimal('spcl_hol', 12, 1)->nullable()->change();
            $table->decimal('leg_hol_ot', 12, 1)->nullable()->change();
            $table->decimal('spcl_hol_ot', 12, 1)->nullable()->change();
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
