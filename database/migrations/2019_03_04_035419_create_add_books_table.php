<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('add_books', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('invoice_ref_id');
            $table->foreign('invoice_ref_id')->references('id')->on('reference_no')->onDelete('cascade');
            $table->unsignedInteger('book_type_id');
            $table->foreign('book_type_id')->references('id')->on('book_type')->onDelete('cascade');
            $table->integer('quantity');
            $table->integer('previous_pending');
            $table->integer('pending');
            $table->integer('book_no_start');
            $table->integer('book_no_end');
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
        Schema::dropIfExists('add_books');
    }
}
