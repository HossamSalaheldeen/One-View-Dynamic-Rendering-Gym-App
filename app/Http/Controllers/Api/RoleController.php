<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RoleResource;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function __invoke(Request $request)
    {
        $filterData = $request->only('name');
        $filterData['name'] = $request->term;
        $roles = Role::query()
            ->select(['id', 'name'])
            ->paginate(self::$perPagePaginator);

        return RoleResource::collection($roles);
    }
}
