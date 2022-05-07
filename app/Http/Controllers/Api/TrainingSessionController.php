<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CityResource;
use App\Http\Resources\RoleResource;
use App\Http\Resources\TrainingSessionResource;
use App\Models\TrainingSession;
use App\Models\TrainingSessionUser;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response;

class TrainingSessionController extends Controller
{
    public function index(Request $request)
    {
        $filterData = $request->only('name');
        $filterData['name'] = $request->term;
        $trainingSessions = TrainingSession::query()
            ->select(['id', 'name'])
            ->paginate(self::$perPagePaginator);

        return TrainingSessionResource::collection($trainingSessions);
    }

    public function remaining()
    {
        $total_training_sessions = TrainingSessionUser::query()
            ->where('user_id',auth()->id())
            ->get()
            ->count();
        $remaining_training_sessions = TrainingSessionUser::query()
            ->where('user_id',auth()->id())
            ->whereNull('time')->get()->count();

        return response([
            'total_training_sessions' => $total_training_sessions,
            'remaining_training_sessions' => $remaining_training_sessions,
        ], Response::HTTP_OK);
    }

    public function history()
    {
//        dd(auth()->id());
        $trainingSessions = TrainingSessionUser::query()
            ->where('user_id',auth()->id())
            ->whereNull('time')->get();

        dd($trainingSessions);
        return \App\Http\Resources\Api\TrainingSessionResource::collection($trainingSessions);
    }

    public function attend(TrainingSession $trainingSession)
    {
        $remaining_training_sessions = TrainingSessionUser::query()
            ->where('user_id',auth()->id())
            ->whereNull('time')->get()->count();

        if ($remaining_training_sessions == 0)
        {
            return response([
                'message' => 'User needs to buy training sessions',
            ], Response::HTTP_FORBIDDEN);
        }
//        else if ($trainingSession->is_attended) {
//            return response([
//                'message' => 'User can not attend session again',
//            ], Response::HTTP_FORBIDDEN);
//        }
        else if ($trainingSession->starts_at > Carbon::now()->toDateTimeString()){
            return response([
                'message' => 'User can not attend session after it starts',
            ], Response::HTTP_FORBIDDEN);
        } else {
//            $trainingSession->update(['is_attended' => true]);

            auth()->user()->trainingSessions()->updateExistingPivot($trainingSession->id, [
                'time' => Carbon::now()->toTimeString(),
                'date' => Carbon::now()->toDateString()
            ]);

            return response([
                'message' => 'User attended session successfully',
            ], Response::HTTP_OK);
        }

    }
}
