import {FoodOption} from './FoodOption';
import {MensaMenuItem} from './MensaMenuItem';
import {ExtraOption} from './ExtraOption';

export interface EditMensa {
  id?: string;
  title: string;
  description: string;
  foodOptions: FoodOption[];
  menu: PartialMensaMenuItem[];
  extraOptions: PartialExtraOption[];
  date: Date;
  closingTime: Date;
  price: number;
  maxSignups: number;
}

export type PartialExtraOption = Omit<ExtraOption, 'id'> & {id?: string};

export type PartialMensaMenuItem = Omit<MensaMenuItem, 'id'> & {id?: string};
