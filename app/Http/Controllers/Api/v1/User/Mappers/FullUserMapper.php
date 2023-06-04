<?php

namespace App\Http\Controllers\Api\v1\User\Mappers;

use App\Http\Controllers\Api\v1\Common\Mappers\FoodOptionsMapper;
use App\Http\Controllers\Api\v1\User\Models\FullUserDto;
use App\Models\User;

class FullUserMapper
{
    public function __construct(
        private readonly FoodOptionsMapper $foodOptionsMapper
    ) {
    }

    public function map(User $user)
    {
        return new FullUserDto(
            id: $user->id,
            name: $user->name,
            email: $user->email,
            allergies: $user->allergies,
            extraInfo: $user->extra_info,
            foodPreference: $this->foodOptionsMapper->fromIntToName($user->food_preference),
            isAdmin: $user->mensa_admin
        );
    }
}
