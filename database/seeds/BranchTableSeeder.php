<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class BranchTableSeeder extends Seeder
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
                'name' => 'Makati',
            ),
            array(
                'name' => 'Naga',
            ),
            array(
                'name' => 'Cebu',
            ),
            array(
                'name' => 'Davao',
            )
        );

        DB::table('branches')->insert($data);
    }
}
