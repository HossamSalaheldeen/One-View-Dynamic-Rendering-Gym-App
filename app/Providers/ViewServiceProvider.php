<?php

namespace App\Providers;

use App\Enums\RoleEnum;
use App\Models\Attendance;
use App\Models\City;
use App\Models\Coach;
use App\Models\Gym;
use App\Models\Revenue;
use App\Models\TrainingPackage;
use App\Models\TrainingSession;
use App\Models\User;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $links = [
            [
                'resource' => 'managers',
                'iconClassName' => 'fas fa-th',
                'allowedRoles' => [RoleEnum::ADMIN,RoleEnum::City_MANAGER],
                'nestedItems' => [
                    [
                        'resource' => City::getTableName(),
                        'iconClassName' => 'far fa-circle',
                        'allowedRoles' => [RoleEnum::ADMIN],
                    ],
                    [
                        'resource' => Gym::getTableName(),
                        'iconClassName' => 'far fa-circle',
                        'allowedRoles' => [RoleEnum::ADMIN,RoleEnum::City_MANAGER],
                    ]
                ]
            ],
            [
                'resource' => City::getTableName(),
                'iconClassName' => 'fas fa-th',
                'allowedRoles' => [RoleEnum::ADMIN,RoleEnum::USER],
            ],
            [
                'resource' => Gym::getTableName(),
                'iconClassName' => 'fas fa-th',
                'allowedRoles' => [RoleEnum::ADMIN,RoleEnum::City_MANAGER,RoleEnum::USER],
            ],
            [
                'resource' => User::getTableName(),
                'iconClassName' => 'fas fa-th',
                'allowedRoles' => [RoleEnum::ADMIN],
            ],
            [
                'resource' => Coach::getTableName(),
                'iconClassName' => 'fas fa-th',
                'allowedRoles' => [RoleEnum::ADMIN,RoleEnum::GYM_MANAGER],
            ],
            [
                'resource' => Str::slug(TrainingSession::getTableName()),
                'iconClassName' => 'fas fa-th',
                'allowedRoles' => [RoleEnum::ADMIN,RoleEnum::GYM_MANAGER,RoleEnum::USER],
            ],
            [
                'resource' => Str::slug(TrainingPackage::getTableName()),
                'iconClassName' => 'fas fa-th',
                'allowedRoles' => [RoleEnum::ADMIN,RoleEnum::City_MANAGER,RoleEnum::GYM_MANAGER,RoleEnum::USER],
            ],
            [
                'resource' => 'payments',
                'iconClassName' => 'fas fa-th',
                'allowedRoles' => [RoleEnum::ADMIN,RoleEnum::City_MANAGER,RoleEnum::GYM_MANAGER,RoleEnum::USER],
            ],
            [
                'resource' => Attendance::getTableName(),
                'iconClassName' => 'fas fa-th',
                'allowedRoles' => [RoleEnum::ADMIN,RoleEnum::City_MANAGER,RoleEnum::GYM_MANAGER,RoleEnum::USER],
            ],
            [
                'resource' => Revenue::getTableName(),
                'iconClassName' => 'fas fa-th',
                'allowedRoles' => [RoleEnum::ADMIN,RoleEnum::City_MANAGER,RoleEnum::GYM_MANAGER],
            ],
        ];

        View::share('links', $links);
    }
}
