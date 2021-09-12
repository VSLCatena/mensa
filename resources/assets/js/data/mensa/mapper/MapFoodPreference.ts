import Result, {runCatching} from "../../../utils/Result";
import FoodPreference from "../../../domain/mensa/model/FoodPreference";

export function MapFoodPreference(foodPreference?: string): Result<FoodPreference|null> {
    return runCatching(() => {
        switch (foodPreference) {
            case 'vegetarian':
                return FoodPreference.VEGETARIAN;
            case 'meat':
                return FoodPreference.MEAT;
            default:
                return null
        }
    });
}