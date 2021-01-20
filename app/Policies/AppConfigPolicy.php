<?php

namespace App\Policies;

use Haxibiao\Breeze\User;
use Haxibiao\Config\AppConfig;
use Illuminate\Auth\Access\HandlesAuthorization;

class AppConfigPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any app configs.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the app config.
     *
     * @param  \App\User  $user
     * @param  \App\AppConfig  $appConfig
     * @return mixed
     */
    public function view(User $user, AppConfig $appConfig)
    {
        return true;

    }

    /**
     * Determine whether the user can create app configs.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;

    }

    /**
     * Determine whether the user can update the app config.
     *
     * @param  \App\User  $user
     * @param  \App\AppConfig  $appConfig
     * @return mixed
     */
    public function update(User $user, AppConfig $appConfig)
    {
        return true;

    }

    /**
     * Determine whether the user can delete the app config.
     *
     * @param  \App\User  $user
     * @param  \App\AppConfig  $appConfig
     * @return mixed
     */
    public function delete(User $user, AppConfig $appConfig)
    {
        return false;

    }

    /**
     * Determine whether the user can restore the app config.
     *
     * @param  \App\User  $user
     * @param  \App\AppConfig  $appConfig
     * @return mixed
     */
    public function restore(User $user, AppConfig $appConfig)
    {
        return true;

    }

    /**
     * Determine whether the user can permanently delete the app config.
     *
     * @param  \App\User  $user
     * @param  \App\AppConfig  $appConfig
     * @return mixed
     */
    public function forceDelete(User $user, AppConfig $appConfig)
    {
        return false;

    }
}
