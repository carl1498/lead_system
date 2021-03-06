<?php

use Illuminate\Database\Seeder;
use App\employee;
use App\emp_salary;

class EmpSalaryDataSeeder extends Seeder
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
            $emp_salary = new emp_salary;
            $emp_salary->emp_id = $e->id;
            $emp_salary->sal_type = 'Monthly';
            $emp_salary->rate = 0;
            $emp_salary->daily = 0;
            $emp_salary->save();
        }
    }
}
