<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReleaseBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('release_books', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('p_request_id');
            $table->foreign('p_request_id')->references('id')->on('pending_request')->onDelete('cascade');
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
        Schema::dropIfExists('release_books');
    }
}
