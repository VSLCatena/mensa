import {JSONSchemaType} from 'ajv';
import {Mensa} from '../../../domain/mensa/model/Mensa';
import {MensaMenuItemSchema} from './MensaMenuItemSchema';
import {ExtraOptionSchema} from './ExtraOptionSchema';
import {IdentifiableUserSchema} from '../../user/schema/IdentifiableUserSchema';

export type UnparsedMensa = Omit<
  Mensa,
  'foodOptions' & 'date' & 'closingTime'
> & {
  foodOptions: string[];
  date: number;
  closingTime: number;
};

export const UnparsedMensaSchema: JSONSchemaType<UnparsedMensa> = {
  type: 'object',
  properties: {
    id: {type: 'string'},
    title: {type: 'string'},
    description: {type: 'string'},
    foodOptions: {
      type: 'array',
      items: {type: 'string'},
    },
    menu: {
      type: 'array',
      items: MensaMenuItemSchema,
    },
    extraOptions: {
      type: 'array',
      items: ExtraOptionSchema,
    },
    date: {type: 'integer'},
    closingTime: {type: 'integer'},
    price: {type: 'number'},
    maxSignups: {type: 'number'},
    signups: {
      oneOf: [
        {type: 'number'},
        {
          type: 'array',
          items: IdentifiableUserSchema,
        },
      ],
    },
    cooks: {
      type: 'array',
      items: IdentifiableUserSchema,
    },
    dishwashers: {type: 'integer'},
  },
  required: [
    'id',
    'title',
    'description',
    'foodOptions',
    'menu',
    'extraOptions',
    'date',
    'closingTime',
    'price',
    'maxSignups',
    'signups',
    'cooks',
    'dishwashers',
  ],
};
