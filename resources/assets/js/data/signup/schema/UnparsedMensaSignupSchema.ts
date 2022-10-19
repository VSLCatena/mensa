import {JSONSchemaType} from 'ajv';
import {MensaSignup} from '../../../domain/signup/model/MensaSignup';

export type UnparsedMensaSignup = Omit<MensaSignup, 'foodOption'> & {
  foodOption: string;
};

export const UnparsedMensaSignupSchema: JSONSchemaType<UnparsedMensaSignup> = {
  type: 'object',
  properties: {
    id: {type: 'string', nullable: true},
    isIntro: {type: 'boolean'},
    foodOption: {type: 'string'},
    extraInfo: {type: 'string'},
    allergies: {type: 'string'},
    cook: {type: 'boolean', nullable: true},
    dishwasher: {type: 'boolean', nullable: true},
  },
  required: ['isIntro', 'foodOption', 'extraInfo', 'allergies'],
  additionalProperties: false,
};
