<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CityManagerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cityManager = User::create([
            'name' => 'cityManager',
            'national_id' => 1234567891011,
            'email' => 'cityManager@cityManager.com',
            'password' => '123456',
            'city_id' => 1,
        ]);

        $cityManager->avatar()->create(['path' => 'default-avatar.png']);

        $cityManager->assignRole(RoleEnum::City_MANAGER);
    }
}
