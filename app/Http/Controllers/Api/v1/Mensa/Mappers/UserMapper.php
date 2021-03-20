<?php

namespace App\Http\Controllers\Api\v1\Mensa\Mappers;

use App\Http\Controllers\Api\v1\Mensa\Models\SimpleUser;
use App\Models\User;

trait UserMapper {
    /**
     * @param User $user
     * @return SimpleUser
     */
    function mapUser(User $user): SimpleUser {
        return new SimpleUser(
            $user->id,
            $user->name,
        );
    }
}
