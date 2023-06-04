<?php

namespace App\Http\Controllers\Api\v1\Common\Mappers;

use App\Http\Controllers\Api\v1\Common\Models\SimpleUserDto;
use App\Models\User;

class SimpleUserMapper
{

    public function map(User $user)
    {
        return new SimpleUserDto(
            id: $user->id,
            name: $user->name
        );
    }
}
