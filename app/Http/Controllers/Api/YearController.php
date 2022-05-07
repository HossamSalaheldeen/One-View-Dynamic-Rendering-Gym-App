<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\YearResource;
use Carbon\Carbon;
use Illuminate\Http\Request;

class YearController extends Controller
{
    public function __invoke(Request $request)
    {
        $startYear = Carbon::createFromFormat('Y',"2015")->year;
        $endYear = Carbon::createFromFormat('Y',"2025")->year;
        $years = range($startYear, $endYear);
        $yearsCollection = collect($years)->map(function ($year,$i){
            return collect([
                'id'=> $i + 1,
                'name' => $year
            ]);
        });
//        dd($yearsCollection);
        return YearResource::collection($yearsCollection);
    }
}
