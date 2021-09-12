<?php
namespace App\Http\Controllers\Api\v1\Mensa\Models;

class MenuItemItem {
    /**
     * MenuItemItem constructor.
     * @param string $id
     * @param int $order
     * @param string $text
     */
    public function __construct(
        public string $id,
        public int $order,
        public string $text,
    ) {
    }
}