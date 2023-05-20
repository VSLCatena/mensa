<?php

namespace App\Policies;

use App\Models\Signup;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SignupPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can edit soft parts of the mensa.
     */
    public function canEdit(?User $user, Signup $signup, ?string $confirmationCode): bool
    {
        $isOwnSignup = $user != null && $signup->user_id == $user->id;
        $isAdmin = $user != null && $user->mensa_admin;
        $confirmationCheck = $signup->confirmation_code == $confirmationCode;

        return $isOwnSignup || $isAdmin || $confirmationCheck;
    }
}
