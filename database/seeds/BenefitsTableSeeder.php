<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class BenefitsTableSeeder extends Seeder
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
                'name' => 'SSS',
            ),
            array(
                'name' => 'Pagibig',
            ),
            array(
                'name' => 'Philhealth',
            ),
            array(
                'name' => 'TIN',
            )
        );

        DB::table('benefits')->insert($data);
    }
}
