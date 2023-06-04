<?php

namespace App\Http\Controllers\Api\v1\Mensa\Mappers;

use App\Http\Controllers\Api\v1\Mensa\Models\MenuItemDto;
use App\Models\MenuItem;

class MenuItemMapper
{
    /**
     * @param $menuItem MenuItem
     * @return MenuItemDto
     */
    public function map(MenuItem $menuItem): MenuItemDto
    {
        return new MenuItemDto(
            id: $menuItem->id,
            text: $menuItem->text
        );
    }

    /**
     * @param  MenuItem[]  $menuItems
     * @return MenuItemDto[]
     */
    public function mapArray(array $menuItems): array
    {
        usort($menuItems, function ($a, $b) {
            return $a->order - $b->order;
        });

        return array_map(function ($item) {
            return self::map($item);
        }, $menuItems);
    }
}
