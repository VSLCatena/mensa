<?php

namespace App\Http\Controllers\Api\v1\Mensa\Models;

class MenuItemResponseModel
{
    /**
     * MenuItemResponseModel constructor.
     * @param string $id
     * @param string $text
     */
    public function __construct(
        public string $id,
        public string $text,
    )
    {
    }
}