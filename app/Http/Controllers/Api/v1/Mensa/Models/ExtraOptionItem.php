<?php

namespace App\Http\Controllers\Api\v1\Mensa\Models;

class ExtraOptionItem {
    /**
     * ExtraOptionItem constructor.
     * @param string $id
     * @param string $description
     * @param float $price
     */
    public function __construct(
        public string $id,
        public string $description,
        public float $price
    ) {
    }
}