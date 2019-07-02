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
                'abbrev' => 'Mon',
                'name' => 'Monday',
            ),
            array(
                'abbrev' => 'Tue',
                'name' => 'Tuesday',
            ),
            array(
                'abbrev' => 'Wed',
                'name' => 'Wednesday',
            ),
            array(
                'abbrev' => 'Thu',
                'name' => 'Thursday',
            ),
            array(
                'abbrev' => 'Fri',
                'name' => 'Friday',
            ),
            array(
                'abbrev' => 'Sat',
                'name' => 'Saturday',
            ),
            array(
                'abbrev' => 'Sun',
                'name' => 'Sunday',
            )
        );
        
        DB::table('day_name')->insert($data);
    }
}
