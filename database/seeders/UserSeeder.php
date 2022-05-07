<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        User::factory(10)->create();
        $user = User::create([
            'name' => 'user',
            'email' => 'user@user.com',
            'password' => '123456',
            'gender' => 'male',
            'date_of_birth' => Carbon::now(),
            'gym_id' => 1,
        ]);

        $user->avatar()->create(['path' => 'default-avatar.png']);

        $user->assignRole(RoleEnum::USER);
    }
}
