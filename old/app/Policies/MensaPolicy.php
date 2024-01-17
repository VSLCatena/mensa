<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Mensa;
use Illuminate\Auth\Access\HandlesAuthorization;

class MensaPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the mensa.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Mensa  $mensa
     * @return mixed
     */
    public function softEdit(User $user, Mensa $mensa)
    {
        return $user->mensa_admin || $mensa->users->where('cooks', '1')->where('lidnummer', $user->lidnummer)->count() > 0;
    }

    /**
     * Determine whether the user can view the mensa.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function hardEdit(User $user)
    {
        return $user->mensa_admin;
    }
}
