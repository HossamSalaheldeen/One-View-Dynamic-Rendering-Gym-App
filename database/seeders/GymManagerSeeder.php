<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GymManagerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $gymManager = User::create([
            'name' => 'gymManager',
            'national_id' => 9876543210364,
            'email' => 'gymManager@gymManager.com',
            'password' => '123456',
            'gym_id' => 1,
        ]);

        $gymManager->avatar()->create(['path' => 'default-avatar.png']);

        $gymManager->assignRole(RoleEnum::GYM_MANAGER);
    }
}
