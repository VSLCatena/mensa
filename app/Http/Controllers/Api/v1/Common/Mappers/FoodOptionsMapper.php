<?php

namespace App\Http\Controllers\Api\v1\Common\Mappers;

use App\Http\Controllers\Api\v1\Common\Models\FoodOptions;

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

    function mapFoodOption(int|null $foodOption): string|null {
        if ($foodOption == null) return null;

        foreach ($this->availableOptions as $key => $value) {
            if ($foodOption & $key) return $value;
        }
        return null;
    }

    function mapFoodOptionsBack(array $foodOptions): int {
        $options = 0;

        foreach ($this->availableOptions as $key => $value) {
            if (array_has($foodOptions, $value)) $options += $key;
        }

        return $options;
    }

    function mapFoodOptionBack(string|null $foodOption): int {
        if ($foodOption == null) return 0;

        foreach ($this->availableOptions as $key => $value) {
            if ($foodOption == $value) return $key;
        }

        return 0;
    }
}
