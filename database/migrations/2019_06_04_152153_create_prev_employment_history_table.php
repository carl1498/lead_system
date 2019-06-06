<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrevEmploymentHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prev_employment_history', function (Blueprint $table) {
            $table->increments('id');
            $table->string('company');
            $table->string('address');
            $table->date('hired_date');
            $table->date('until');
            $table->decimal('salary', 10, 2)->nullable();
            $table->string('designation');
            $table->string('employment_type');
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
        Schema::dropIfExists('prev_employment_history');
    }
}
