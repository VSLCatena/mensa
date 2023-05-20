import {JSONSchemaType} from 'ajv';
import {Faq} from '../../../domain/faq/model/Faq';
import {FaqSchema} from './FaqSchema';

export const FaqListSchema: JSONSchemaType<Faq[]> = {
  type: 'array',
  items: FaqSchema,
  required: ['id', 'question', 'answer'],
  additionalProperties: false,
};
