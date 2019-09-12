<?php

use Illuminate\Database\Seeder;

class TfNameTableSeeder extends Seeder
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
                'name' => 'Sign Up Fee',
            ),
            array(
                'name' => 'Visa Processing Fee'
            ),
            array(
                'name' => 'Language Tuition Fee',
            ),
            array(
                'name' => 'Documentation Fee',
            ),
            array(
                'name' => 'Selection Fee',
            ),
            array(
                'name' => 'PDOS',
            ),
        );

        DB::table('tf_name')->insert($data);
    }
}
