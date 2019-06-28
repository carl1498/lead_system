<?php

use Illuminate\Database\Seeder;

class DayNameTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = array(
            array(
                'abbrev' => 'M',
                'name' => 'Monday',
            ),
            array(
                'abbrev' => 'Tu',
                'name' => 'Tuesday',
            ),
            array(
                'abbrev' => 'W',
                'name' => 'Wednesday',
            ),
            array(
                'abbrev' => 'Th',
                'name' => 'Thursday',
            ),
            array(
                'abbrev' => 'F',
                'name' => 'Friday',
            ),
            array(
                'abbrev' => 'Sa',
                'name' => 'Saturday',
            ),
            array(
                'abbrev' => 'Su',
                'name' => 'Sunday',
            )
        );
        
        DB::table('day_name')->insert($data);
    }
}
