<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTfProjectedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tf_projected', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('tf_name_id');
            $table->foreign('tf_name_id')->references('id')->on('tf_name');
            $table->unsignedInteger('program_id');
            $table->foreign('program_id')->references('id')->on('programs');
            $table->decimal('amount', 12, 2);
            $table->string('date_of_payment')->nullable();
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
        Schema::dropIfExists('tf_projected');
    }
}
