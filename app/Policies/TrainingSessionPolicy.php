<?php

namespace App\Policies;

use App\Models\TrainingSession;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Access\HandlesAuthorization;

class TrainingSessionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TrainingSession  $trainingSession
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, TrainingSession $trainingSession)
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return true;
    }

    public function edit(User $user, TrainingSession $trainingSession)
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TrainingSession  $trainingSession
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, TrainingSession $trainingSession)
    {

        return true;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TrainingSession  $trainingSession
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, TrainingSession $trainingSession)
    {
        if ($trainingSession->users()->exists())
        {
            return $this->deny('Can not delete this gym because it has related users');
        }
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TrainingSession  $trainingSession
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, TrainingSession $trainingSession)
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TrainingSession  $trainingSession
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, TrainingSession $trainingSession)
    {
        return true;
    }

    public function change(User $user, TrainingSession $trainingSession)
    {
        if ($trainingSession->users()->exists())
        {
            return $this->deny('Can not delete this gym because it has related users');
        }
        return true;
    }

    public function attend(User $user, TrainingSession $trainingSession)
    {
        $checkAllowedInterval = Carbon::now()->between($trainingSession->starts_at,$trainingSession->finishes_at);

//        dd($checkAllowedInterval);

        if(!$checkAllowedInterval){
            return $this->deny("Session interval between $trainingSession->starts_at and $trainingSession->finishes_at" );
        }

//        $checkSessionIsAttended = TrainingSession::query()
//            ->whereHas('users', function ($q) use ($trainingSession) {
//                $q
//                    ->where('training_session_user.user_id', auth()->user()->id)
//                    ->where('training_session_user.training_session_id', $trainingSession->id)
//                    ->whereNotNull('training_session_user.time');
//            })
//            ->exists();
//
//        if($checkSessionIsAttended){
//            return $this->deny('session already attended');
//        }

        return true;
    }
}
