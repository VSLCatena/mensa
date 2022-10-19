import {FoodOption} from '../../../domain/mensa/model/FoodOption';
import {checkIsArray, requireNotNull} from '../../utils/MappingUtils';

export class FoodOptionMapper {
  async mapList(preferences: string[]): Promise<FoodOption[]> {
    checkIsArray('foodOptions', preferences);
    return Promise.all(
      preferences.map((preference: string) =>
        requireNotNull('preferences', this.map(preference))
      )
    );
  }

  map(foodPreference?: string): FoodOption | null {
    switch (foodPreference) {
      case 'vegan':
        return FoodOption.VEGAN;
      case 'vegetarian':
        return FoodOption.VEGETARIAN;
      case 'meat':
        return FoodOption.MEAT;
      default:
        return null;
    }
  }
}
