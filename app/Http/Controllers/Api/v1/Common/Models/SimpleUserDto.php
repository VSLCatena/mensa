<?php

namespace App\Http\Controllers\Api\v1\Common\Models;

class SimpleUserDto
{
    public function __construct(
        public string $id,
        public string $name
    ) {
    }
}
