<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Application;
use App\Models\User;

class ApplicationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Application  $application
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Application $application): bool
    {
        return $user->id === $application->citizen_id || $user->id === $application->mediator_id || $user->id === $application->judge_id;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Application  $application
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Application $application)
    {
        return $user->id === $application->citizen_id || $user->id === $application->mediator_id || $user->id === $application->judge_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Application  $application
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Application $application)
    {
        return $user->id === $application->citizen_id || $user->id === $application->judge_id;
    }
}
