import {JSONSchemaType} from 'ajv';
import {Faq} from '../../../domain/faq/model/Faq';

export const FaqSchema: JSONSchemaType<Faq> = {
  type: 'object',
  properties: {
    id: {type: 'string'},
    question: {type: 'string'},
    answer: {type: 'string'},
  },
  required: ['id', 'question', 'answer'],
  additionalProperties: false,
};
