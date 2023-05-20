import {JSONSchemaType} from 'ajv';
import {AppConfig} from '../../../domain/appconfig/model/AppConfig';

export const AppConfigSchema: JSONSchemaType<AppConfig> = {
  type: 'object',
  properties: {
    defaultMensaOptions: {
      type: 'object',
      properties: {
        title: {type: 'string'},
        price: {type: 'integer'},
        maxSignups: {type: 'integer'},
      },
      required: ['title', 'price', 'maxSignups'],
    },
  },
  required: ['defaultMensaOptions'],
};
