import {JSONSchemaType} from 'ajv';
import {AuthorizationToken} from '../model/AuthorizationToken';

export const AuthorizationTokenSchema: JSONSchemaType<AuthorizationToken> = {
  type: 'object',
  properties: {
    token: {type: 'string'},
  },
  required: ['token'],
  additionalProperties: false,
};
