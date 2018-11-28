<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class RolesTableSeeder extends Seeder
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
                'name' => 'Admin',
            ),
            array(
                'name' => 'Manager',
            ),
            array(
                'name' => 'HR Head',
            ),
            array(
                'name' => 'HR',
            ),
            array(
                'name' => 'IT Officer',
            ),
            array(
                'name' => 'Marketing Head',
            ),
            array(
                'name' => 'Marketing Officer',
            ),
            array(
                'name' => 'Documentation Head',
            ),
            array(
                'name' => 'Documentation Officer',
            ),
            array(
                'name' => 'Language Head',
            ),
            array(
                'name' => 'Language Teacher',
            ),
        );

        DB::table('roles')->insert($data);
    }
}
