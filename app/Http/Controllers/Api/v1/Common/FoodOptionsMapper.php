<?php

namespace App\Http\Controllers\Api\v1\Common;

use App\Http\Controllers\Api\v1\Mensa\Models\FoodOptions;

trait FoodOptionsMapper {

    private array $availableOptions = array(
        FoodOptions::VEGAN => 'vegan',
        FoodOptions::VEGETARIAN => 'vegetarian',
        FoodOptions::MEAT => 'meat',
    );

    /**
     * @param $foodOptions int
     * @return int[]
     */
    function mapFoodOptions(int $foodOptions): array {
        $options = array();
        foreach ($this->availableOptions as $key => $value) {
            if ($foodOptions & $key) $options[] = $value;
        }

        return $options;
    }

    function mapFoodOption(int|null $foodOption): int|null {
        if ($foodOption == null) return null;

        foreach ($this->availableOptions as $key => $value) {
            if ($foodOption & $key) return $value;
        }
        return null;
    }
}
