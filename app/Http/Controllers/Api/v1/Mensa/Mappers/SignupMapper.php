<?php

namespace App\Http\Controllers\Api\v1\Mensa\Mappers;

use App\Http\Controllers\Api\v1\Mensa\Models\SignupItem;
use App\Models\Signup;
use App\Models\User;

trait SignupMapper
{
    use UserMapper;

    /**
     * @param Signup $signup
     * @param User|null $user
     * @return SignupItem
     */
    function mapSignup(Signup $signup, User $user = null): SignupItem
    {
        return new SignupItem(
            id: $signup->id,
            user: self::mapUser($user ?? $signup->user),
            allergies: $signup->allergies,
            extraInfo: $signup->extra_info,
            food_option: $signup->food_option,
            cooks: $signup->cooks,
            dishwasher: $signup->dishwasher
        );
    }
}
