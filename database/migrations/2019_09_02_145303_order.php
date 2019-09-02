<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Order extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('order_type_id');
            $table->foreign('order_type_id')->references('id')->on('order_type');
            $table->unsignedInteger('client_id');
            $table->foreign('client_id')->references('id')->on('client');
            $table->integer('no_of_orders');
            $table->integer('no_of_hires');
            $table->string('start_time')->nullable();
            $table->string('end_time')->nullable();
            $table->date('interview_date')->nullable();
            $table->string('remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();
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
