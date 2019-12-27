<?php

namespace App\Policies;

use App\AdConfig;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdConfigPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any ad configs.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the ad config.
     *
     * @param  \App\User  $user
     * @param  \App\AdConfig  $adConfig
     * @return mixed
     */
    public function view(User $user, AdConfig $adConfig)
    {
        return true;

    }

    /**
     * Determine whether the user can create ad configs.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;

    }

    /**
     * Determine whether the user can update the ad config.
     *
     * @param  \App\User  $user
     * @param  \App\AdConfig  $adConfig
     * @return mixed
     */
    public function update(User $user, AdConfig $adConfig)
    {
        return true;

    }

    /**
     * Determine whether the user can delete the ad config.
     *
     * @param  \App\User  $user
     * @param  \App\AdConfig  $adConfig
     * @return mixed
     */
    public function delete(User $user, AdConfig $adConfig)
    {
        return false; //默认不允许用户删除这个配置
    }

    /**
     * Determine whether the user can restore the ad config.
     *
     * @param  \App\User  $user
     * @param  \App\AdConfig  $adConfig
     * @return mixed
     */
    public function restore(User $user, AdConfig $adConfig)
    {
        return true;

    }

    /**
     * Determine whether the user can permanently delete the ad config.
     *
     * @param  \App\User  $user
     * @param  \App\AdConfig  $adConfig
     * @return mixed
     */
    public function forceDelete(User $user, AdConfig $adConfig)
    {
        return false;
    }
}
