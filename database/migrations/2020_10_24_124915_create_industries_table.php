<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIndustriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('industries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
        });

        $data = array(
            array(
                'name' => 'Nursing Care',
            ),
            array(
                'name' => 'Food Service',
            ),
            array(
                'name' => 'Building Maintenance',
            ),
            array(
                'name' => 'Airport Handling',
            ),
            array(
                'name' => 'Hotel',
            ),
            array(
                'name' => 'Food & Beverage Manufacturing',
            )
        );

        DB::table('industries')->insert($data);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('industries');
    }
}
