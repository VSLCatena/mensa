<?php

namespace App\Http\Controllers\Api\v1\Mensa\Mappers;

use App\Http\Controllers\Api\v1\Mensa\Models\SimpleUserItem;
use App\Http\Controllers\Api\v1\Utils\User\UserCache;
use App\Models\Signup;
use App\Models\User;

trait UserMapper
{
    /**
     * @param User $user
     * @return SimpleUserItem
     */
    function mapUser(User $user): SimpleUserItem
    {
        return new SimpleUserItem(
            $user->id,
            $user->name,
        );
    }

    function mapUserFromSignup(Signup $signup): SimpleUserItem
    {
        return $this->mapUser(UserCache::getCachedUser($signup->user_id));
    }
}
