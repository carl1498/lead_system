<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RecreateSecBondTfPayment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::drop('sec_bond');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        Schema::create('sec_bond', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('stud_id');
            $table->foreign('stud_id')->references('id')->on('students');
            $table->decimal('amount', 12, 2);
            $table->date('date');
            $table->string('remarks')->nullable();
            $table->timestamps();
        });
        
        Schema::create('tf_payment', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('stud_id');
            $table->foreign('stud_id')->references('id')->on('students');
            $table->unsignedInteger('tf_name_id');
            $table->foreign('tf_name_id')->references('id')->on('tf_name');
            $table->decimal('amount', 12, 2);
            $table->date('date');
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
