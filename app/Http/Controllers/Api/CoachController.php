<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CoachResource;
use App\Models\Coach;
use Illuminate\Http\Request;

class CoachController extends Controller
{
    public function __invoke(Request $request)
    {
        $filterData = $request->only('name');
        $filterData['name'] = $request->term;
        $Coaches = Coach::query()
            ->select(['id', 'name'])
            ->paginate(self::$perPagePaginator);

        return CoachResource::collection($Coaches);
    }
}
