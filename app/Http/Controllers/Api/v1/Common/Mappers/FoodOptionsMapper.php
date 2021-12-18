<?php

namespace App\Http\Controllers\Api\v1\Common\Mappers;

use App\Http\Controllers\Api\v1\Common\Models\FoodOption;

trait FoodOptionsMapper {

    /**
     * @param $foodOptions int
     * @return string[]
     */
    function mapFoodOptionsFromIntToNames(int $foodOptions): array {
        $options = array();
        foreach (FoodOption::$All as $option) {
            if ($foodOptions & $option->value) $options[] = $option->name;
        }

        return $options;
    }

    /**
     * @param int|null $foodOption
     * @return string|null
     */
    function mapFoodOptionFromIntToName(int|null $foodOption): string|null {
        if ($foodOption == null) return null;

        foreach (FoodOption::$All as $option) {
            if ($foodOption & $option->value) return $option->name;
        }
        return null;
    }

    /**
     * @param string[] $foodOptions
     * @return int
     */
    function mapFoodOptionsFromNamesToInt(array $foodOptions): int {
        $options = 0;

        foreach (FoodOption::$All as $option) {
            if (in_array($option->name, $foodOptions)) $options += $option->value;
        }

        return $options;
    }

    /**
     * @param string|null $foodOption
     * @return int
     */
    function mapFoodOptionFromNameToInt(string|null $foodOption): int {
        if ($foodOption == null) return 0;

        foreach (FoodOption::$All as $option) {
            if ($foodOption == $option->name) return $option->value;
        }

        return 0;
    }
}
