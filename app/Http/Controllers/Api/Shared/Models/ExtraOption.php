<?php

namespace App\Http\Controllers\Api\Shared\Models;

class ExtraOption {
    public function __construct(
        public string $id,
        public string $description,
    ) {
    }
}