<?php

namespace App\Http\Controllers\Api\Shared\Mappers;

use App\Http\Controllers\Api\Shared\Models\SimpleUser;
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
