<?php

namespace App\Http\Controllers\Api\v1\Common\Mappers;

use App\Http\Controllers\Api\v1\Common\Models\FoodOption;

trait FoodOptionsMapper
{
    /**
     * @param $foodOptions int
     * @return string[]
     */
    public function mapFoodOptionsFromIntToNames(int $foodOptions): array
    {
        $options = [];
        foreach (FoodOption::$All as $option) {
            if ($foodOptions & $option->value) {
                $options[] = $option->name;
            }
        }

        return $options;
    }

    public function mapFoodOptionFromIntToName(int|null $foodOption): string|null
    {
        if ($foodOption == null) {
            return null;
        }

        foreach (FoodOption::$All as $option) {
            if ($foodOption & $option->value) {
                return $option->name;
            }
        }

        return null;
    }

    /**
     * @param  string[]  $foodOptions
     */
    public function mapFoodOptionsFromNamesToInt(array $foodOptions): int
    {
        $options = 0;

        foreach (FoodOption::$All as $option) {
            if (in_array($option->name, $foodOptions)) {
                $options += $option->value;
            }
        }

        return $options;
    }

    public function mapFoodOptionFromNameToInt(string|null $foodOption): int
    {
        if ($foodOption == null) {
            return 0;
        }

        foreach (FoodOption::$All as $option) {
            if ($foodOption == $option->name) {
                return $option->value;
            }
        }

        return 0;
    }
}
