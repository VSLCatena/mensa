<?php

namespace App\Http\Controllers\Api\v1\Mensa\Mappers;

use App\Http\Controllers\Api\v1\Common\Mappers\FoodOptionsMapper;
use App\Http\Controllers\Api\v1\Mensa\Models\SignupDto;
use App\Models\Signup;

class SignupMapper
{
    public function __construct(
        private readonly FoodOptionsMapper $foodOptionsMapper
    )
    {
    }

    /**
     * @param Signup $signup
     * @return SignupDto
     */
    public function map(Signup $signup): SignupDto
    {
        return new SignupDto(
            id: $signup->id,
            allergies: $signup->allergies,
            extraInfo: $signup->extra_info,
            foodOption: $this->foodOptionsMapper->fromIntToName($signup->food_option),
            cooks: $signup->cooks,
            dishwasher: $signup->dishwasher
        );
    }
}
