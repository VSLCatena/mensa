<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Mensa;
use Illuminate\Auth\Access\HandlesAuthorization;

class MensaPolicy
{
    use HandlesAuthorization;


    /**
     * Determine whether the user can create a mensa.
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool {
        return $user->mensa_admin;
    }

    /**
     * Determine whether the user can edit soft parts of the mensa.
     *
     * @param User $user
     * @param Mensa $mensa
     * @return bool
     */
    public function softEdit(User $user, Mensa $mensa): bool {
        return $user->mensa_admin || $this->isCook($user, $mensa);
    }

    /**
     * Determine whether the user can edit hard parts of the mensa.
     *
     * @param User $user
     * @param Mensa $mensa
     * @return bool
     */
    public function hardEdit(User $user, Mensa $mensa): bool {
        return $user->mensa_admin;
    }

    /**
     * Determine whether the user can delete the mensa.
     * @param User $user
     * @param Mensa $mensa
     * @return bool
     */
    public function delete(User $user, Mensa $mensa): bool {
        return $user->mensa_admin;
    }

    /**
     * Can the given user see the mensa overview
     *
     * @param User $user
     * @param Mensa $mensa
     * @return bool
     */
    public function seeOverview(User $user, Mensa $mensa): bool {
        return $user->mensa_admin || $this->isCook($user, $mensa);
    }

    /**
     * Checks if the given user is a cook
     *
     * @param User $user
     * @param Mensa $mensa
     * @return bool
     */
    private function isCook(User $user, Mensa $mensa): bool {
        return $mensa->users()->where('cooks', '1')->where('id', $user->id)->count() > 0;
    }
}
