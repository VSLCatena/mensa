<?php

namespace App\Http\Controllers\Api\Shared\Models;

class SimpleUser {

    /**
     * SimpleUser constructor.
     * @param string $id
     * @param string $name
     */
    public function __construct(
        public string $id,
        public string $name,
    ) {
    }
}