<?php

use Illuminate\Database\Seeder;
use App\employee;

class EmployeesCompanyTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $employee = employee::all();

        foreach($employee as $e){
            $e->lead_company_type_id = 1;
            $e->save();
        }
    }
}
