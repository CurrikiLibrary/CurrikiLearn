<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CustomGroupUserRolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('custom_group_user_roles')->insertUsing(
            ['group_id', 'user_id', 'role_id', 'created_at', 'updated_at'],
            function ($query) {
                $query->select([
                            DB::raw(env('NASSAU_HUB_ID')),
                            'user_id', 
                            DB::raw('(CASE WHEN is_admin = 1 THEN 1 WHEN is_mod = 1 THEN 2 ELSE 3 END) AS role_id'),
                            DB::raw('"'.Carbon::now()->format('Y-m-d H:i:s').'"'),
                            DB::raw('"'.Carbon::now()->format('Y-m-d H:i:s').'"')
                        ])
                        ->from('cur_bp_groups_members')->where('group_id', '=', env('CURRIKI_NASSAU_GROUP_ID'));
            }
        );
    }
}
