<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => '123456',
        ]);

        $admin->avatar()->create(['path' => 'default-avatar.png']);

        $admin->assignRole(RoleEnum::ADMIN);
    }
}
