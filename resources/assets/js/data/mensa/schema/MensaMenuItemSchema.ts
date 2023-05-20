import {JSONSchemaType} from 'ajv';
import {MensaMenuItem} from '../../../domain/mensa/model/MensaMenuItem';

export const MensaMenuItemSchema: JSONSchemaType<MensaMenuItem> = {
  type: 'object',
  properties: {
    id: {type: 'string'},
    text: {type: 'string'},
  },
  required: ['id', 'text'],
  additionalProperties: false,
};
