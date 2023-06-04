<?php

namespace App\Http\Controllers\Api\v1\Mensa\Models;

class MensaUserWithSignupDto extends MensaUserDto
{
    /**
     * SimpleUserDto constructor.
     */
    public function __construct(
        string $id,
        string $name,
        bool $isIntro,
        public SignupDto $signup
    ) {
        parent::__construct($id, $name, $isIntro);
    }
}
