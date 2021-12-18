<?php

namespace App\Http\Controllers\Api\v1\User\Models;

class UserSelf
{
    public function __construct(
        public string $id,
        public string $name,
        public string $email,
        public string|null $allergies,
        public string|null $extraInfo,
        public string|null $foodPreference,
        public bool $isAdmin
    )
    {
    }
}