<?php

namespace App\Http\Controllers\Api\v1\Mensa\Mappers;

use App\Http\Controllers\Api\v1\Mensa\Models\SimpleUserItem;
use App\Models\User;

trait UserMapper {
    /**
     * @param User $user
     * @return SimpleUserItem
     */
    function mapUser(User $user): SimpleUserItem {
        return new SimpleUserItem(
            $user->id,
            $user->name,
        );
    }
}
