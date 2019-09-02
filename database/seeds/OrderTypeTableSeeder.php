<?php

use Illuminate\Database\Seeder;

class OrderTypeTableSeeder extends Seeder
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
                'name' => 'Student',
            ),
            array(
                'name' => 'SSW',
            ),
            array(
                'name' => 'Internship',
            ),
            array(
                'name' => 'EMI',
            ),
            array(
                'name' => 'TITP',
            ),
            array(
                'name' => 'Others',
            )
        );

        DB::table('order_type')->insert($data);
    }
}
