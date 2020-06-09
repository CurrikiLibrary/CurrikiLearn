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
        $this->call([
            CustomGroupTypesTableSeeder::class,
            CustomUserRolesTableSeeder::class,
            CustomResourceVisibilityOptionsTableSeeder::class,
            CustomGroupsTableSeeder::class,
            CustomGroupResourcesTableSeeder::class,
            CustomGroupResourceVisibilityOptionsTableSeeder::class,
            CustomGroupUsersTableSeeder::class,
            CustomGroupUserRolesTableSeeder::class,
            CustomLevelGroupingsTableSeeder::class,
            CustomLevelGroupingLevelsTableSeeder::class,
        ]);
    }
}
