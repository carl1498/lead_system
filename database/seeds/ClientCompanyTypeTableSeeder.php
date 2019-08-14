<?php

use Illuminate\Database\Seeder;

class ClientCompanyTypeTableSeeder extends Seeder
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
                'name' => 'Japanese School',
            ),
            array(
                'name' => 'Medical Facility',
            ),
            array(
                'name' => 'Manpower Agency',
            )
        );

        DB::table('client_company_type')->insert($data);
    }
}
