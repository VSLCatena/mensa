<?php

namespace App\Http\Controllers\Api\v1\Mensa\Models;

class ExtraOptionItem {
    /**
     * ExtraOptionItem constructor.
     * @param string $id
     * @param string $description
     * @param int $order
     * @param float $price
     */
    public function __construct(
        public string $id,
        public string $description,
        public int $order,
        public float $price
    ) {
    }
}