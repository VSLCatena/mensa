import {JSONSchemaType} from 'ajv';
import {FullUser} from '../../../domain/common/model/User';

export type UnparsedFullUser = Omit<FullUser, 'foodPreference'> & {
  foodPreference: string | null;
};

export const UnparsedFullUserSchema: JSONSchemaType<UnparsedFullUser> = {
  type: 'object',
  properties: {
    id: {type: 'string'},
    name: {type: 'string'},
    email: {type: 'string'},
    isAdmin: {type: 'boolean'},
    foodPreference: {type: 'string'},
    extraInfo: {type: 'string', nullable: true},
    allergies: {type: 'string', nullable: true},
  },
  required: [
    'id',
    'name',
    'email',
    'isAdmin',
    'foodPreference',
    'extraInfo',
    'allergies',
  ],
  additionalProperties: false,
};
