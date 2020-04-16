<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CustomGroupUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('custom_group_users')->insertUsing(
            ['group_id', 'user_id', 'created_at', 'updated_at'],
            function ($query) {
                $query->select([DB::raw(env('APP_HUB_ID')), 'user_id', DB::raw('"'.Carbon::now()->format('Y-m-d H:i:s').'"'), DB::raw('"'.Carbon::now()->format('Y-m-d H:i:s').'"')])->from('cur_bp_groups_members')->where('group_id', '=', env('CURRIKI_GROUP_ID'));
            }
        );
    }
}
