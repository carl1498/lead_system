<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class EmployeeBenefitsTableSeeder extends Seeder
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
                'emp_id' => 1,
                'benefits_id' => 1,
                'id_number' => '1234',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ),
            array(
                'emp_id' => 1,
                'benefits_id' => 2,
                'id_number' => '4321',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ),
            array(
                'emp_id' => 1,
                'benefits_id' => 3,
                'id_number' => '5678',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ),
            array(
                'emp_id' => 1,
                'benefits_id' => 4,
                'id_number' => '8765',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ),
        );

        DB::table('employee_benefits')->insert($data);
    }
}
