<?php

namespace App\Http\Controllers\Api\v1\Mensa\Mappers;

use App\Http\Controllers\Api\v1\Mensa\Models\SignupResponseModel;
use App\Models\Signup;
use App\Models\User;

trait SignupMapper
{
    use UserMapper;

    /**
     * @param Signup $signup
     * @param User|null $user
     * @return SignupResponseModel
     */
    function mapSignup(Signup $signup, User $user = null): SignupResponseModel
    {
        return new SignupResponseModel(
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
