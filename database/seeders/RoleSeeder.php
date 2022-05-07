<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['name' => RoleEnum::ADMIN ]);
        Role::create(['name' => RoleEnum::City_MANAGER ]);
        Role::create(['name' => RoleEnum::GYM_MANAGER ]);
        Role::create(['name' => RoleEnum::USER ]);
    }
}
