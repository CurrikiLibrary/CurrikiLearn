<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CustomGroupResourceVisibilityOptionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('custom_group_resource_visibility_options')->insertUsing(
            ['group_id', 'resource_id', 'visibility_id', 'created_at', 'updated_at'],
            function ($query) {
                $query->select(['group_id', 'resource_id', DB::raw('3'), DB::raw('"'.Carbon::now()->format('Y-m-d H:i:s').'"'), DB::raw('"'.Carbon::now()->format('Y-m-d H:i:s').'"')])->from('custom_group_resources')->where('group_id', '=', env('NASSAU_HUB_ID'));
            }
        );
    }
}
