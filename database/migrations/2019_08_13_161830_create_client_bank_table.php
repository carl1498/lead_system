<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientBankTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_bank', function (Blueprint $table) {
            $table->increments('id');
            $table->string('bank_name');
            $table->string('swift_code');
            $table->string('branch_name');
            $table->string('account_name');
            $table->string('account_number');
            $table->string('address');
            $table->string('contact')->nullable();
            $table->unsignedInteger('client_id');
            $table->foreign('client_id')->references('id')->on('client');
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
        Schema::dropIfExists('client_bank');
    }
}
