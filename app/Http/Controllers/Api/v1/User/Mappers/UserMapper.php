<?php

namespace App\Http\Controllers\Api\v1\User\Mappers;

use App\Http\Controllers\Api\v1\User\Models\UserSelf;
use App\Models\User;

trait UserMapper {
    function mapUser(User $user) {
        return new UserSelf(
            id: $user->id,
            name: $user->name,
            email: $user->email,
            allergies: $user->allergies,
            extraInfo: $user->extra_info,
            foodPreference: $user->food_preference,
            mensaAdmin: $user->mensa_admin
        );
    }
}
