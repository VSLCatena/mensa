import {ExtraOption} from './ExtraOption';
import {IdentifiableUser} from '../../common/model/User';
import {MensaMenuItem} from './MensaMenuItem';
import {FoodOption} from './FoodOption';

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
  signups: number | IdentifiableUser[];
  cooks: IdentifiableUser[];
  dishwashers: number;
}
