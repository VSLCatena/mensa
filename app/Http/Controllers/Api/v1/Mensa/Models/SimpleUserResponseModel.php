<?php

namespace App\Http\Controllers\Api\v1\Mensa\Models;

class SimpleUserResponseModel
{
    /**
     * SimpleUserResponseModel constructor.
     */
    public function __construct(
        public string $id,
        public string $name,
        public ?SignupResponseModel $signup,
    ) {
    }
}
