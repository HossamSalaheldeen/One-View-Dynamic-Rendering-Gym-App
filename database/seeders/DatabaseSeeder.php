<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            RoleSeeder::class,
//            PermissionSeeder::class,
            AdminSeeder::class,
            CitySeeder::class,
            CityManagerSeeder::class,
            GymSeeder::class,
            GymManagerSeeder::class,
            CoachSeeder::class,
            UserSeeder::class,
        ]);
    }
}
