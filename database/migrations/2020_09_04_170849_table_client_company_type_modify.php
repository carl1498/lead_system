<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\client_company_type;

class TableClientCompanyTypeModify extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_company_type', function (Blueprint $table) {
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
        });

        Schema::table('order', function (Blueprint $table) {
            $table->dropForeign('order_client_id_foreign');
            $table->dropIndex('order_client_id_foreign');
        });

        Schema::table('client', function (Blueprint $table) {
            $table->dropForeign('client_client_company_type_id_foreign');
            $table->dropIndex('client_client_company_type_id_foreign');
        });
        
        Schema::table('client', function (Blueprint $table) {
            $table->foreign('client_company_type_id')->references('id')->on('client_company_type')->onDelete('cascade');
        });
        
        Schema::table('order', function (Blueprint $table) {
            $table->foreign('client_id')->references('id')->on('client')->onDelete('cascade');
        });


        $client = client_company_type::where('name', 'Medical Facility')->first();
        $client->delete();
        $client = client_company_type::where('name', 'Manpower Agency')->first();
        $client->name = 'POEA Agency';
        $client->save();
        $client = client_company_type::where('name', 'Benefactor')->first();
        $client->name = 'SSW Benefactor';
        $client->save();
        $client = client_company_type::where('name', 'FTS')->first();
        $client->name = 'TITP';
        $client->save();
        $client = new client_company_type;
        $client->name = 'RSO';
        $client->save();
        $client = new client_company_type;
        $client->name = 'Others';
        $client->save();
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
