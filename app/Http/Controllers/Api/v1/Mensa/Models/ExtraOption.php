<?php

namespace App\Http\Controllers\Api\v1\Mensa\Models;

class ExtraOption {
    public function __construct(
        public string $id,
        public string $description,
    ) {
    }
}