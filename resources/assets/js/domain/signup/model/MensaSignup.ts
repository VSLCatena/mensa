import {FoodOption} from '../../mensa/model/FoodOption';

export interface MensaSignup {
  id?: string;
  foodOption: FoodOption | null;
  isIntro: boolean;
  extraInfo: string;
  allergies: string;
  cook?: boolean;
  dishwasher?: boolean;
}
