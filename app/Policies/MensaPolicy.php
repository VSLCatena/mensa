<?php

namespace App\Policies;

use App\Models\Mensa;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MensaPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create a mensa.
     */
    public function create(User $user): bool
    {
        return $this->isMensaAdmin($user);
    }

    /**
     * Determine whether the user can edit soft parts of the mensa.
     */
    public function softEdit(?User $user, Mensa $mensa): bool
    {
        return $user != null && ($this->hardEdit($user, $mensa) || $this->isCook($user, $mensa));
    }

    /**
     * Determine whether the user can edit hard parts of the mensa.
     */
    public function hardEdit(?User $user, Mensa $mensa): bool
    {
        return $this->isMensaAdmin($user);
    }

    /**
     * Determine whether the user can delete the mensa.
     */
    public function delete(User $user, Mensa $mensa): bool
    {
        return $this->isMensaAdmin($user);
    }

    /**
     * Can the given user see the mensa overview
     */
    public function seeOverview(User $user, Mensa $mensa): bool
    {
        return $this->isMensaAdmin($user) || $this->isCook($user, $mensa);
    }

    /**
     * Checks if the given user is a cook
     */
    private function isCook(User $user, Mensa $mensa): bool
    {
        return $mensa->signups()->where('cooks', '1')->where('user_id', $user->id)->count() > 0;
    }

    private function isMensaAdmin(?User $user): bool
    {
        return optional($user)->mensa_admin == true;
    }
}
