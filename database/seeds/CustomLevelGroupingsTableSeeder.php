<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomLevelGroupingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('custom_level_groupings')->insert([
            [
                'name' => 'preschool',
                'display_name' => 'Preschool'
            ],
            [
                'name' => 'gk2',
                'display_name' => 'Grades K-2'
            ],
            [
                'name' => 'g35',
                'display_name' => 'Grades 3-5'
            ],
            [
                'name' => 'middleschool',
                'display_name' => 'Middle School'
            ],
            [
                'name' => 'highschool',
                'display_name' => 'High School'
            ]
        ]);
    }
}
