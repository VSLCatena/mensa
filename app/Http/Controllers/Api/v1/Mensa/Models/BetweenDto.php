<?php

namespace App\Http\Controllers\Api\v1\Mensa\Models;

class BetweenDto
{
    /**
    * Between constructor.
    */
    public function __construct(
        public int $from,
        public int $to
    )
    {
    }
}