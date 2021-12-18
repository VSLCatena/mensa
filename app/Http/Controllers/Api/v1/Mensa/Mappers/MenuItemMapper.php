<?php

namespace App\Http\Controllers\Api\v1\Mensa\Mappers;

use App\Http\Controllers\Api\v1\Mensa\Models\MenuItemItem;
use App\Models\MenuItem;

trait MenuItemMapper
{

    /**
     * @param $menuItem MenuItem
     * @return MenuItemItem
     */
    function mapMenuItem(MenuItem $menuItem): MenuItemItem
    {
        return new MenuItemItem(
            id: $menuItem->id,
            text: $menuItem->text
        );
    }

    /**
     * @param MenuItem[] $menuItems
     * @return MenuItemItem[]
     */
    function mapMenuItems(array $menuItems): array
    {
        usort($menuItems, function ($a, $b) {
            return $a->order - $b->order;
        });
        return array_map(function ($item) {
            return self::mapMenuItem($item);
        }, $menuItems);
    }
}
