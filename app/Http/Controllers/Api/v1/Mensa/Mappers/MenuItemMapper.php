<?php

namespace App\Http\Controllers\Api\v1\Mensa\Mappers;

use App\Http\Controllers\Api\v1\Mensa\Models\MenuItemResponseModel;
use App\Models\MenuItem;

trait MenuItemMapper
{
    /**
     * @param $menuItem MenuItem
     */
    public function mapMenuItem(MenuItem $menuItem): MenuItemResponseModel
    {
        return new MenuItemResponseModel(
            id: $menuItem->id,
            text: $menuItem->text
        );
    }

    /**
     * @param  MenuItem[]  $menuItems
     * @return MenuItemResponseModel[]
     */
    public function mapMenuItems(array $menuItems): array
    {
        usort($menuItems, function ($a, $b) {
            return $a->order - $b->order;
        });

        return array_map(function ($item) {
            return self::mapMenuItem($item);
        }, $menuItems);
    }
}
