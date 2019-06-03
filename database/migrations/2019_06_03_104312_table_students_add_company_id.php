<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TableStudentsAddCompanyId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE students ADD company_id INT UNSIGNED NULL');
        DB::statement('ALTER TABLE students ADD CONSTRAINT students_company_id_foreign
                        FOREIGN KEY (company_id) REFERENCES company(id)');
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
