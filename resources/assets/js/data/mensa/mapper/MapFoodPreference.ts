import Result, {runCatching} from "../../../utils/Result";
import FoodPreference from "../../../domain/mensa/model/FoodPreference";
import {requireNotNull} from "../../utils/MappingUtils";

export function MapFoodPreferences(preferences: string[]): Result<FoodPreference[]> {
    return runCatching(() => {
        if (!Array.isArray(preferences))
            throw new Error("data is not of type Array. ("+(typeof preferences)+")");

        return preferences.map(function(preference: any) {
            return requireNotNull('foodPreferences', MapFoodPreference(preference).getOrThrow());
        });
    })
}

export function MapFoodPreference(foodPreference?: string): Result<FoodPreference|null> {
    return runCatching(() => {
        switch (foodPreference) {
            case 'vegan':
                return FoodPreference.VEGAN;
            case 'vegetarian':
                return FoodPreference.VEGETARIAN;
            case 'meat':
                return FoodPreference.MEAT;
            default:
                return null
        }
    });
}