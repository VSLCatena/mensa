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
        return $this->isMensaAdmin($user);
    }

    /**
     * Determine whether the user can edit soft parts of the mensa.
     *
     * @param User|null $user
     * @param Mensa $mensa
     * @return bool
     */
    public function softEdit(?User $user, Mensa $mensa): bool {
        return $this->hardEdit($user, $mensa) || $this->isCook($user, $mensa);
    }

    /**
     * Determine whether the user can edit hard parts of the mensa.
     *
     * @param User|null $user
     * @param Mensa $mensa
     * @return bool
     */
    public function hardEdit(?User $user, Mensa $mensa): bool {
        return $this->isMensaAdmin($user);
    }

    /**
     * Determine whether the user can delete the mensa.
     * @param User $user
     * @param Mensa $mensa
     * @return bool
     */
    public function delete(User $user, Mensa $mensa): bool {
        return $this->isMensaAdmin($user);
    }

    /**
     * Can the given user see the mensa overview
     *
     * @param User $user
     * @param Mensa $mensa
     * @return bool
     */
    public function seeOverview(User $user, Mensa $mensa): bool {
        return $this->isMensaAdmin($user) || $this->isCook($user, $mensa);
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

    private function isMensaAdmin(?User $user): bool {
        return optional($user)->mensa_admin == true;
    }
}
