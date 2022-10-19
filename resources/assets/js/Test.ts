import Ajv, {JSONSchemaType} from 'ajv';

export enum FoodOption {
  VEGAN = 'vegan',
  VEGETARIAN = 'vegetarian',
  MEAT = 'meat',
}

export const SortedFoodOptions: FoodOption[] = [
  FoodOption.VEGAN,
  FoodOption.VEGETARIAN,
  FoodOption.MEAT,
];

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

export interface MensaMenuItem {
  id: string;
  text: string;
}

export const MensaMenuItemSchema: JSONSchemaType<MensaMenuItem> = {
  type: 'object',
  properties: {
    id: {type: 'string'},
    text: {type: 'string'},
  },
  required: ['id', 'text'],
  additionalProperties: false,
};

export interface ExtraOption {
  id: string;
  description: string;
  price: number;
}

export const ExtraOptionSchema: JSONSchemaType<ExtraOption> = {
  type: 'object',
  properties: {
    id: {type: 'string'},
    description: {type: 'string'},
    price: {type: 'number'},
  },
  required: ['id', 'description', 'price'],
  additionalProperties: false,
};

export interface IdentifiableUser {
  id: string;
  name: string;
}

export const IdentifiableUserSchema: JSONSchemaType<IdentifiableUser> = {
  type: 'object',
  properties: {
    id: {type: 'string'},
    name: {type: 'string'},
  },
  required: ['id', 'name'],
  additionalProperties: false,
};

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
    // signups: {
    //   type: 'number'
    // },
    // signups: {
    //   type: 'array',
    //   items: IdentifiableUserSchema
    // },
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

export interface UnparsedBetween {
  start: number;
  end: number;
}

export interface UnparsedMensaList {
  between: UnparsedBetween;
  mensas: UnparsedMensa[];
}

const BetweenSchema: JSONSchemaType<UnparsedBetween> = {
  type: 'object',
  properties: {
    start: {type: 'integer'},
    end: {type: 'integer'},
  },
  required: ['start', 'end'],
};

export const MensaListSchema: JSONSchemaType<UnparsedMensaList> = {
  type: 'object',
  properties: {
    between: BetweenSchema,
    mensas: {
      type: 'array',
      items: UnparsedMensaSchema,
    },
  },
  required: ['between', 'mensas'],
};

const ajv = new Ajv();
const validate = ajv.compile(MensaListSchema);

const data = {
  between: {
    start: 1665957600,
    end: 1667170799,
  },
  mensas: [
    {
      id: 'c0e3da37-2280-4a94-bee0-264fb7664319',
      title: 'Illum qui aut sed.',
      description: 'Pariatur est consectetur',
      date: 1666214121,
      closingTime: 1666214121,
      isClosed: true,
      maxSignups: 38,
      signups: 28,
      price: 6.78,
      dishwashers: 4,
      cooks: [
        {
          id: '58a8a03b-4371-3f5e-b0f4-b80d8bbad8e2',
          name: 'Giovanna Stehr',
        },
        {
          id: '49a6379e-d1e7-3262-99ae-9893bf21e2d8',
          name: 'Montana Feeney',
        },
      ],
      foodOptions: ['vegan'],
      menu: [
        {
          id: 'f6fc4486-9e7f-32c9-a78f-c35d0a23266d',
          text: 'Optio expedita voluptatum.',
        },
      ],
      extraOptions: [],
    },
  ],
};

// const data = {
//   "between": {
//     "start": 1665957600,
//     "end": 1667170799
//   },
//   "mensas": [{
//     "id": "c0e3da37-2280-4a94-bee0-264fb7664319",
//     "title": "Illum qui aut sed.",
//     "description": "Pariatur est consectetur",
//     "date": 1666214121,
//     "closingTime": 1666214121,
//     "isClosed": true,
//     "maxSignups": 38,
//     "signups": [{
//       "id": "58a8a03b-4371-3f5e-b0f4-b80d8bbad8e2",
//       "name": "Giovanna Stehr"
//     }, {
//       "id": "49a6379e-d1e7-3262-99ae-9893bf21e2d8",
//       "name": "Montana Feeney"
//     }],
//     "price": 6.78,
//     "dishwashers": 4,
//     "cooks": [{
//       "id": "58a8a03b-4371-3f5e-b0f4-b80d8bbad8e2",
//       "name": "Giovanna Stehr"
//     }, {
//       "id": "49a6379e-d1e7-3262-99ae-9893bf21e2d8",
//       "name": "Montana Feeney"
//     }],
//     "foodOptions": ["vegan"],
//     "menu": [{
//       "id": "f6fc4486-9e7f-32c9-a78f-c35d0a23266d",
//       "text": "Optio expedita voluptatum."
//     }],
//     "extraOptions": []
//   }]
// };

console.debug(validate(data));
console.debug(validate.errors);
