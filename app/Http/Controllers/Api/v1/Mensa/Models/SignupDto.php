<?php

namespace App\Http\Controllers\Api\v1\Mensa\Models;

class SignupDto
{
    /**
     * SignupDto constructor.
     */
    public function __construct(
        public string $id,
        public ?string $allergies,
        public ?string $extraInfo,
        public string $foodOption,
        public bool $cooks,
        public bool $dishwasher
    ) {
    }
}
