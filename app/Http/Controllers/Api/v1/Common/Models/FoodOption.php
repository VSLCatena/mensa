<?php

namespace App\Http\Controllers\Api\v1\Common\Models;

class FoodOption
{

    function __construct(
        public string $name,
        public int $value
    )
    {
    }


    public static FoodOption $VEGAN;
    public static FoodOption $VEGETARIAN;
    public static FoodOption $MEAT;

    /** @var FoodOption[] */
    public static array $All;

    public static function allNames(): array
    {
        return array_map(function (FoodOption $option): string {
            return $option->name;
        }, FoodOption::$All);
    }
}

FoodOption::$VEGAN = new FoodOption('vegan', 1 << 0);
FoodOption::$VEGETARIAN = new FoodOption('vegetarian', 1 << 1);
FoodOption::$MEAT = new FoodOption('meat', 1 << 2);

FoodOption::$All = [FoodOption::$VEGAN, FoodOption::$VEGETARIAN, FoodOption::$MEAT];