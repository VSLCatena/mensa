<?php

namespace App\Http\Controllers\Api\v1\Mensa\Mappers;

use App\Http\Controllers\Api\v1\Mensa\Models\MenuItemItem;
use App\Models\MenuItem;

trait MenuItemMapper {

    /**
     * @param $menuItem MenuItem
     * @return MenuItemItem
     */
    function mapMenuItem($menuItem): MenuItemItem {
        return new MenuItemItem(
            id: $menuItem->id,
            text: $menuItem->text
        );
    }
}
