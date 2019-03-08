<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('name');
            $table->unsignedInteger('stud_id')->nullable();
            $table->foreign('stud_id')->references('id')->on('students')->onDelete('cascade');
            $table->unsignedInteger('book_type_id');
            $table->foreign('book_type_id')->references('id')->on('book_type')->onDelete('cascade');
            $table->unsignedInteger('invoice_ref_id');
            $table->foreign('invoice_ref_id')->references('id')->on('reference_no')->onDelete('cascade');
            $table->unsignedInteger('branch_id');
            $table->foreign('branch_id')->references('id')->on('branches');
            $table->enum('status', ['Available', 'Released', 'Lost']);
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
        Schema::dropIfExists('books');
    }
}
