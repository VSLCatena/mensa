<?php

namespace App\Http\Controllers\Api\v1\User\Mappers;

use App\Http\Controllers\Api\v1\Common\Mappers\FoodOptionsMapper;
use App\Http\Controllers\Api\v1\User\Models\UserSelf;
use App\Models\User;

trait UserMapper {
    use FoodOptionsMapper;

    function mapUser(User $user) {
        return new UserSelf(
            id: $user->id,
            name: $user->name,
            email: $user->email,
            allergies: $user->allergies,
            extraInfo: $user->extra_info,
            foodPreference: $this->mapFoodOption($user->food_preference),
            isAdmin: $user->mensa_admin
        );
    }
}
