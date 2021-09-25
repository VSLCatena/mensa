<?php

namespace App\Http\Controllers\Api\v1\Mensa\Mappers;

use App\Http\Controllers\Api\v1\Mensa\Models\FoodOptions;

trait FoodOptionsMapper {

    /**
     * @param $foodOptions int
     * @return int[]
     */
    function mapFoodOptions(int $foodOptions): array {
        $options = array();
        if ($foodOptions & FoodOptions::VEGAN) $options[] = 'vegan';
        if ($foodOptions & FoodOptions::VEGETARIAN) $options[] = 'vegetarian';
        if ($foodOptions & FoodOptions::MEAT) $options[] = 'meat';

        return $options;
    }
}
