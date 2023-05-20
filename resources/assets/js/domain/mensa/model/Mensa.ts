import {ExtraOption} from './ExtraOption';
import {IdentifiableUser} from '../../common/model/User';
import {MensaMenuItem} from './MensaMenuItem';
import {FoodOption} from './FoodOption';
import {SignupUser} from "./SignupUser";

export interface Mensa {
  id: string;
  title: string;
  description: string;
  foodOptions: FoodOption[];
  menu: MensaMenuItem[];
  extraOptions: ExtraOption[];
  date: Date;
  closingTime: Date;
  price: number;
  maxSignups: number;
  signups: number | SignupUser[];
  cooks: IdentifiableUser[];
  dishwashers: number;
}
