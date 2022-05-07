<?php

namespace App\Http\Controllers\Api;

use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __invoke(Request $request)
    {
        $filterData = $request->only('name');
        $filterData['name'] = $request->term;
        $users = User::query()
            ->role(RoleEnum::USER)
            ->select(['id', 'name'])
            ->paginate(self::$perPagePaginator);

        return UserResource::collection($users);
    }
}
