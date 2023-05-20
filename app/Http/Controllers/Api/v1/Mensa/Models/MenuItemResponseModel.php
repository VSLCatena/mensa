<?php

namespace App\Http\Controllers\Api\v1\Mensa\Models;

class MenuItemResponseModel
{
    /**
     * MenuItemResponseModel constructor.
     */
    public function __construct(
        public string $id,
        public string $text,
    ) {
    }
}
