<?php

use Illuminate\Database\Seeder;

class BookTypeTableSeeder extends Seeder
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
                'name' => 'Book 1',
                'description' => 'Minna no Nihongo I'
            ),
            array(
                'name' => 'WB 1',
                'description' => 'Minna no Nihongo I WB'
            ),
            array(
                'name' => 'Book 2',
                'description' => 'Minna no Nihongo II'
            ),
            array(
                'name' => 'WB 2',
                'description' => 'Minna No Nihongo II WB'
            ),
            array(
                'name' => 'Kanji',
                'description' => 'Kanji'
            )
        );

        DB::table('book_type')->insert($data);
    }
}
