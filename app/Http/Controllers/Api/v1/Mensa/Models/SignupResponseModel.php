<?php

namespace App\Http\Controllers\Api\v1\Mensa\Models;

class SignupResponseModel
{

    /**
     * SimpleUserResponseModel constructor.
     * @param string $id
     * @param SimpleUserResponseModel $user
     * @param string $allergies
     * @param string $extraInfo
     * @param int $food_option
     * @param bool $cooks
     * @param bool $dishwasher
     */
    public function __construct(
        public string $id,
        public SimpleUserResponseModel $user,
        public string $allergies,
        public string $extraInfo,
        public int $food_option,
        public bool $cooks,
        public bool $dishwasher
    )
    {
    }
}