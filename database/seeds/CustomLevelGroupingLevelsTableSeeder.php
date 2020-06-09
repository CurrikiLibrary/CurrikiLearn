<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomLevelGroupingLevelsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('custom_level_grouping_levels')->insert([
            [
                'level_grouping_id' => 1,
                'level_id' => 9
            ],
            [
                'level_grouping_id' => 2,
                'level_id' => 8
            ],
            [
                'level_grouping_id' => 2,
                'level_id' => 3
            ],
            [
                'level_grouping_id' => 2,
                'level_id' => 4
            ],
            [
                'level_grouping_id' => 3,
                'level_id' => 5
            ],
            [
                'level_grouping_id' => 3,
                'level_id' => 6
            ],
            [
                'level_grouping_id' => 3,
                'level_id' => 7
            ],
            [
                'level_grouping_id' => 4,
                'level_id' => 11
            ],
            [
                'level_grouping_id' => 4,
                'level_id' => 12
            ],
            [
                'level_grouping_id' => 4,
                'level_id' => 13
            ],
            [
                'level_grouping_id' => 5,
                'level_id' => 15
            ],
            [
                'level_grouping_id' => 5,
                'level_id' => 16
            ],
            [
                'level_grouping_id' => 5,
                'level_id' => 17
            ],
            [
                'level_grouping_id' => 5,
                'level_id' => 18
            ]
        ]);
    }
}
