import {JSONSchemaType} from 'ajv';
import {IdentifiableUser} from '../../../domain/common/model/User';

export const IdentifiableUserSchema: JSONSchemaType<IdentifiableUser> = {
  type: 'object',
  properties: {
    id: {type: 'string'},
    name: {type: 'string'},
  },
  required: ['id', 'name'],
  additionalProperties: false,
};
