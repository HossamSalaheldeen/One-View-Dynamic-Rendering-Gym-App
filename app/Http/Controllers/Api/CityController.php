<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CityResource;
use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function __invoke(Request $request)
    {
        $filterData = $request->only('name');
        $filterData['name'] = $request->term;
        $cities = City::query()
            ->select(['id', 'name'])
            ->paginate(self::$perPagePaginator);

        return CityResource::collection($cities);
    }
}
