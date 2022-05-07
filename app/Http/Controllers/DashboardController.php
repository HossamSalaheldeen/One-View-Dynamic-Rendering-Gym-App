<?php

namespace App\Http\Controllers;

use App\Enums\RoleEnum;
use App\Models\City;
use App\Models\Gym;
use App\Models\Revenue;
use App\Models\TrainingSessionUser;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use function Doctrine\Common\Cache\Psr6\get;

class DashboardController extends Controller
{
    private $resource;

    public function __construct()
    {
        $this->middleware('role:'.RoleEnum::ADMIN.'|'.RoleEnum::City_MANAGER.'|'.RoleEnum::GYM_MANAGER);

        $this->resource = 'dashboard';

    }

    public function index(Request $request)
    {

        $year = $request->year ? $request->year : Carbon::now()->year;

        $revenueGroups = Revenue::query()
            ->selectRaw('MONTH(created_at) month, SUM(amount) total')
            ->whereRaw('year(created_at) = ?', [$year])
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $revenuesData = [];


        for ($m=1; $m<=12; $m++) {
            $revenuesData['label'][] = date('F', mktime(0,0,0,$m, 1, date('Y')));
            $revenuesData ['data'][] = 0;
        }

        foreach ($revenueGroups as $key => $group) {
            $revenuesData ['data'][$group->month] = $group->total;
        }

//        dd($revenuesData);

        $userGroups = User::query()->select(['id', 'name'])
            ->role(RoleEnum::USER)
            ->withCount(['trainingSessions' => function($q) use($year){
                $q->whereYear('training_session_user.created_at', '=', $year);
            }])
            ->orderBy('training_sessions_count', 'desc')
            ->limit(10)
            ->get()
            ->groupBy('name');

        $usersData = [];

        foreach ($userGroups as $key => $group) {
            $usersData ['label'][] = $key;
            $usersData ['data'][] = $group->first()->training_sessions_count;
        }

        $cityGroups = TrainingSessionUser::query()
            ->with(['gym:id,city_id', 'gym.city' => function ($q) {
                $q->select(['id', 'name']);
            }])
            ->attended(true)
            ->whereYear('created_at', '=', $year)
            ->get()->groupBy('gym.city.name');
        $citiesData = [];

        foreach ($cityGroups as $key => $group) {
            $citiesData ['label'][] = $key;
            $citiesData ['data'][] = $group->count();
        }


        $genderGroups = TrainingSessionUser::query()
            ->with(['user' => function ($q) {
                $q->select(['id', 'gender'])->role(RoleEnum::USER);
            }])
            ->attended(true)
            ->whereYear('created_at', '=', $year)
            ->get()->groupBy('user.gender');


        $genderData = [];

        foreach ($genderGroups as $key => $group) {
            $genderData ['label'][] = $key;
            $genderData ['data'][] = $group->count();
        }

        $isEdit = false;
        $resource = $this->resource;
        $fields = [];

        if ($request->ajax())
            return view($this->resource.'.scripts', get_defined_vars());

        return view($this->resource.'.index', get_defined_vars());
    }
}
