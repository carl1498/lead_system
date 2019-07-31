<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Expense extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expense', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('expense_type_id');
            $table->foreign('expense_type_id')->references('id')->on('expense_type');
            $table->unsignedInteger('expense_particular_id');
            $table->foreign('expense_particular_id')->references('id')->on('expense_particular');
            $table->unsignedInteger('branch_id');
            $table->foreign('branch_id')->references('id')->on('branches');
            $table->unsignedInteger('lead_company_type_id');
            $table->foreign('lead_company_type_id')->references('id')->on('lead_company_type');
            $table->date('date');
            $table->decimal('amount', 12, 2);
            $table->string('vat');
            $table->decimal('input_tax', 12, 2);
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
        //
    }
}
