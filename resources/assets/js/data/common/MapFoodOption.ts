import Result, {runCatching} from "../../domain/common/utils/Result";
import FoodOption from "../../domain/mensa/model/FoodOption";
import {checkIsArray, requireNotNull} from "../utils/MappingUtils";

export function MapFoodOptions(preferences: string[]): Result<FoodOption[]> {
    return runCatching(() => {
        checkIsArray('foodOptions', preferences);
        return preferences.map(function(preference: any) {
            return requireNotNull('foodOptions', MapFoodOption(preference).getOrThrow());
        });
    })
}

export function MapFoodOption(foodPreference?: string): Result<FoodOption|null> {
    return runCatching(() => {
        switch (foodPreference) {
            case 'vegan':
                return FoodOption.VEGAN;
            case 'vegetarian':
                return FoodOption.VEGETARIAN;
            case 'meat':
                return FoodOption.MEAT;
            default:
                return null
        }
    });
}