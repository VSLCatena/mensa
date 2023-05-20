<?php

namespace App\Http\Controllers\Api\v1\Mensa\Models;

class ExtraOptionResponseModel
{
    /**
     * ExtraOptionResponseModel constructor.
     */
    public function __construct(
        public string $id,
        public string $description,
        public int $order,
        public float $price
    ) {
    }
}
