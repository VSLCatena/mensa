import {JSONSchemaType} from 'ajv';
import {UnparsedMensaSchema, UnparsedMensa} from './UnparsedMensaSchema';

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
