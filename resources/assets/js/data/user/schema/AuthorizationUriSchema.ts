import {JSONSchemaType} from 'ajv';
import {AuthorizationUri} from '../model/AuthorizationUri';

export const AuthorizationUriSchema: JSONSchemaType<AuthorizationUri> = {
  type: 'object',
  properties: {
    authorizationUri: {type: 'string'},
  },
  required: ['authorizationUri'],
  additionalProperties: false,
};
