<?php

namespace App\Http\Controllers\Api\v1\Mensa\Models;

class MensaUserDto
{
    /**
     * SimpleUserDto constructor.
     */
    public function __construct(
        public string     $id,
        public string     $name,
        public bool       $isIntro
    ) {
    }
}
