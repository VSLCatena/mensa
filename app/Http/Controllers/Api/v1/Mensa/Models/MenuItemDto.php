<?php

namespace App\Http\Controllers\Api\v1\Mensa\Models;

class MenuItemDto
{
    /**
     * MenuItemDto constructor.
     */
    public function __construct(
        public string $id,
        public string $text,
    ) {
    }
}
