<?php

namespace App\Http\Controllers\Api\v1\Mensa\Models;

class SignupResponseModel
{
    /**
     * SimpleUserResponseModel constructor.
     */
    public function __construct(
        public string $id,
        public ?string $allergies,
        public ?string $extraInfo,
        public int $foodOption,
        public bool $cooks,
        public bool $dishwasher
    ) {
    }
}
