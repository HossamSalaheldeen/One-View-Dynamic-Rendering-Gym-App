<?php

namespace Database\Seeders;

use App\Enums\PermissionEnum;
use App\Models\Attachment;
use App\Models\Attendance;
use App\Models\City;
use App\Models\Coach;
use App\Models\Gym;
use App\Models\Revenue;
use App\Models\TrainingPackage;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            PermissionEnum::VIEW_ANY,
            PermissionEnum::VIEW,
            PermissionEnum::CREATE,
            PermissionEnum::UPDATE,
            PermissionEnum::DELETE,
            PermissionEnum::BAN,
        ];

        $modelClasses = [
            City::class,
            Gym::class,
            User::class,
            TrainingPackage::class,
            Attachment::class,
            Coach::class,
            Attendance::class,
            Revenue::class
        ];

        foreach ($modelClasses as $modelClass) {
            foreach ($permissions as $permission) {
                Permission::create(['name' => getPermissionName($permission, $modelClass)]);
            }
        }
    }
}
