<?php

namespace App\Http\Controllers\Api\v1\Mensa\Models;

class ExtraOptionDto
{
    /**
     * ExtraOptionDto constructor.
     */
    public function __construct(
        public string $id,
        public string $description,
        public float $price
    ) {
    }
}
