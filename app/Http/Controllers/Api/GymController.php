<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GymResource;
use App\Models\Gym;
use Illuminate\Http\Request;

class GymController extends Controller
{
    public function __invoke(Request $request)
    {
        $filterData = $request->only('name');
        $filterData['name'] = $request->term;
        $gyms = Gym::query()
            ->select(['id', 'name'])
            ->paginate(self::$perPagePaginator);

        return GymResource::collection($gyms);
    }
}
