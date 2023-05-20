<?php

namespace App\Http\Controllers\Api\v1\Mensa\Mappers;

use App\Http\Controllers\Api\v1\Mensa\Models\SignupResponseModel;
use App\Http\Controllers\Api\v1\Mensa\Models\SimpleUserResponseModel;
use App\Models\User;

trait UserMapper
{
    public function mapUser(User $user, ?SignupResponseModel $signup): SimpleUserResponseModel
    {
        return new SimpleUserResponseModel(
            id: $user->id,
            name: $user->name,
            signup: $signup
        );
    }
}
