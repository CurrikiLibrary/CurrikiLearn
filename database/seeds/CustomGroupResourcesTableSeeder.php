<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CustomGroupResourcesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('custom_group_resources')->insertUsing(
            ['group_id', 'resource_id', 'created_at', 'updated_at'],
            function ($query) {
                $query->select([DB::raw(env('NASSAU_HUB_ID')), 'resourceid', DB::raw('"'.Carbon::now()->format('Y-m-d H:i:s').'"'), DB::raw('"'.Carbon::now()->format('Y-m-d H:i:s').'"')])->from('group_resources')->where('groupid', '=', env('CURRIKI_NASSAU_GROUP_ID'));
            }
        );
    }
}
