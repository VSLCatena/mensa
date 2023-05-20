<?php

namespace App\Http\Controllers\Api\v1\Mensa\Mappers;

use App\Http\Controllers\Api\v1\Mensa\Models\SignupResponseModel;
use App\Models\Signup;

trait SignupMapper
{
    use UserMapper;

    public function mapSignup(Signup $signup): SignupResponseModel
    {
        return new SignupResponseModel(
            id: $signup->id,
            allergies: $signup->allergies,
            extraInfo: $signup->extra_info,
            foodOption: $signup->food_option,
            cooks: $signup->cooks,
            dishwasher: $signup->dishwasher
        );
    }
}
