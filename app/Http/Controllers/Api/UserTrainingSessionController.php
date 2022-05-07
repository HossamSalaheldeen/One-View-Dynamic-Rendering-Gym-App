<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TrainingSessionResource;
use App\Models\TrainingSession;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserTrainingSessionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
    {
        $trainingSessions = $user->trainingSessions()->get();
        return TrainingSessionResource::collection($trainingSessions);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $user)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\User $user
     * @param \App\Models\TrainingSession $trainingSession
     * @return \Illuminate\Http\Response
     */
    public function show(User $user, TrainingSession $trainingSession)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     * @param \App\Models\TrainingSession $trainingSession
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user, TrainingSession $trainingSession)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\User $user
     * @param \App\Models\TrainingSession $trainingSession
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user, TrainingSession $trainingSession)
    {
        //
    }

    public function attend(User $user, TrainingSession $trainingSession)
    {
        if ($trainingSession->is_attended) {
            return response([
                'message' => 'User can not attend session again',
            ], Response::HTTP_FORBIDDEN);
        } elseif ($trainingSession->starts_at > Carbon::now()->toDateTimeString()){
            return response([
                'message' => 'User can not attend session after it starts',
            ], Response::HTTP_FORBIDDEN);
        } else {
            $trainingSession->update(['is_attended' => true]);

            $user->trainingSessions()->updateExistingPivot($trainingSession->id, [
                'time' => Carbon::now()->toTimeString(),
                'date' => Carbon::now()->toDateString()
            ]);

            return response([
                'message' => 'User attended session successfully',
            ], Response::HTTP_OK);
        }

    }
}
