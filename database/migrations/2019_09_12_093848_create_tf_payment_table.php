<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTfPaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tf_payment', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('tf_stud_id');
            $table->foreign('tf_stud_id')->references('id')->on('tf_student');
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
        Schema::dropIfExists('tf_payment');
    }
}
