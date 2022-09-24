<?php

namespace App\Http\Controllers\Api\v1\Mensa\Mappers;

use App\Http\Controllers\Api\v1\Mensa\Models\SimpleUserResponseModel;
use App\Http\Controllers\Api\v1\Utils\User\UserCache;
use App\Models\Signup;
use App\Models\User;

trait UserMapper
{
    /**
     * @param User $user
     * @return SimpleUserResponseModel
     */
    function mapUser(User $user): SimpleUserResponseModel
    {
        return new SimpleUserResponseModel(
            id: $user->id,
            name: $user->name,
        );
    }

    function mapUserFromSignup(Signup $signup): SimpleUserResponseModel
    {
        return $this->mapUser(UserCache::getCachedUser($signup->user_id));
    }
}
