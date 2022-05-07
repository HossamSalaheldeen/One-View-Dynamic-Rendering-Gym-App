<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RoleResource;
use App\Http\Resources\TrainingPackageResource;
use App\Models\TrainingPackage;
use Illuminate\Http\Request;

class TrainingPackageController extends Controller
{
    public function __invoke(Request $request)
    {
        $filterData = $request->only('name');
        $filterData['name'] = $request->term;
        $trainingPackages = TrainingPackage::query()
            ->select(['id', 'name'])
            ->paginate(self::$perPagePaginator);

        return TrainingPackageResource::collection($trainingPackages);
    }
}
