<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class EmployeesTableSeeder extends Seeder
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
                'fname' => 'Benedict',
                'mname' => 'Sandoval',
                'lname' => 'Badilles',
                'birthdate' => Carbon::parse('1998-01-04'),
                'contact_personal' => '09209599219',
                'contact_business' => null,
                'salary' => 200,
                'picture' => null,
                'address' => 'Davao City',
                'Email' => 'benbenbadilles@gmail.com',
                'branch_id' => '1',
                'gender' => 'Male',
                'employment_status' => 'Active',
                'role_id' => 1,
                'hired_date' => Carbon::parse('2018-07-16'),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            )
        );
        
        DB::table('employees')->insert($data);
    }
}
