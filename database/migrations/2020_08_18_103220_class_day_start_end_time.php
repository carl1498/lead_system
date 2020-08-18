<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\time;
use App\class_day;

class ClassDayStartEndTime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('class_day', function (Blueprint $table) {
            $table->dropForeign('class_day_start_time_id_foreign');
            $table->dropIndex('class_day_start_time_id_foreign');
            $table->dropForeign('class_day_end_time_id_foreign');
            $table->dropIndex('class_day_end_time_id_foreign');
        });

        Schema::table('class_day', function (Blueprint $table) {
            $table->renameColumn('start_time_id', 'start_time');
            $table->renameColumn('end_time_id', 'end_time');
        });

        Schema::table('class_day', function (Blueprint $table) {
            $table->string('start_time')->nullable()->change();
            $table->string('end_time')->nullable()->change();
        });

        $class_day = class_day::get();

        foreach($class_day as $cd){
            if($cd->start_time){
                $id = $cd->start_time;
                $time = time::find($id);
                $cd->start_time = (string) $time->name;
                $cd->save();
            }
            
            if($cd->end_time){
                $id = $cd->end_time;
                $time = time::find($id);
                $cd->end_time = (string) $time->name;
                $cd->save();
            }
        }

        Schema::dropIfExists('time');
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
