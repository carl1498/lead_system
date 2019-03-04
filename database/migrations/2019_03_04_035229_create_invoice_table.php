<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('ref_no_id');
            $table->foreign('ref_no_id')->references('id')->on('reference_no')->onDelete('cascade');
            $table->unsignedInteger('book_type_id');
            $table->foreign('book_type_id')->references('id')->on('book_type')->onDelete('cascade');
            $table->integer('quantity');
            $table->integer('pending');
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
        Schema::dropIfExists('invoice');
    }
}
