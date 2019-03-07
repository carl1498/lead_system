<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //GENERAL -- START
        $this->call(BranchTableSeeder::class);
        //GENERAL -- END

        //EMPLOYEE -- START
        $this->call(RolesTableSeeder::class);
        $this->call(BenefitsTableSeeder::class);
        $this->call(EmployeesTableSeeder::class);
        $this->call(EmployeeBenefitsTableSeeder::class);
        //EMPLOYEE -- END

        //SCHOOL -- START
        $this->call(DepartureYearTableSeeder::class);
        $this->call(DepartureMonthTableSeeder::class);
        $this->call(BenefactorTableSeeder::class);
        $this->call(SchoolTableSeeder::class);
        $this->call(ProgramTableSeeder::class);
        $this->call(StudentsTableSeeder::class);
        //SCHOOL -- END

        //USER -- START
        $this->call(UsersTableSeeder::class);
        //USER -- END

        //BOOKS -- START
        $this->call(BookTypeTableSeeder::class);
        //BOOKS -- END
    }
}
