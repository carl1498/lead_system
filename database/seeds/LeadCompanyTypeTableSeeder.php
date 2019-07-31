<?php

use Illuminate\Database\Seeder;

class LeadCompanyTypeTableSeeder extends Seeder
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
                'name' => 'LEAD',
            ),
            array(
                'name' => 'MILA',
            ),
            array(
                'name' => 'ANK',
            )
        );

        DB::table('lead_company_type')->insert($data);
    }
}
