<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class StudentSettingsTablesUnique extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('programs', function (Blueprint $table){
            $table->string('name')->unique()->change();
        });

        Schema::table('schools', function (Blueprint $table){
            $table->string('name')->unique()->change();
        });

        Schema::table('benefactors', function (Blueprint $table){
            $table->string('name')->unique()->change();
        });

        Schema::table('departure_years', function (Blueprint $table){
            $table->string('name')->unique()->change();
        });

        Schema::table('departure_months', function (Blueprint $table){
            $table->string('name')->unique()->change();
        });

        Schema::table('courses', function (Blueprint $table){
            $table->string('name')->unique()->change();
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
