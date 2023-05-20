import {JSONSchemaType} from 'ajv';
import {ExtraOption} from '../../../domain/mensa/model/ExtraOption';

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
