<?php

namespace App\Policies;

use App\Models\TrainingPackage;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TrainingPackagePolicy
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
     * @param  \App\Models\TrainingPackage  $trainingPackage
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, TrainingPackage $trainingPackage)
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

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TrainingPackage  $trainingPackage
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, TrainingPackage $trainingPackage)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TrainingPackage  $trainingPackage
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, TrainingPackage $trainingPackage)
    {
        if ( $trainingPackage->users()->exists())
        {
            return $this->deny('Can not delete this training package because it has users');
        }

        if ( $trainingPackage->revenues()->exists())
        {
            return $this->deny('Can not delete this training package because it has revenues from it');
        }
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TrainingPackage  $trainingPackage
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, TrainingPackage $trainingPackage)
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TrainingPackage  $trainingPackage
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, TrainingPackage $trainingPackage)
    {
        return true;
    }
}
