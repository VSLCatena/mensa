import {JSONSchemaType} from "ajv";
import {SignupUser} from "../../../domain/mensa/model/SignupUser";
import {UnparsedMensaSignup, UnparsedMensaSignupSchema} from "../../signup/schema/UnparsedMensaSignupSchema";

export type UnparsedSignupUser = Omit<SignupUser, 'signup'> & {
  signup?: UnparsedMensaSignup;
}
export const UnparsedSignupUserSchema: JSONSchemaType<UnparsedSignupUser> = {
  type: 'object',
  properties: {
    id: {type: 'string'},
    name: {type: 'string'},
    signup: {
      ...UnparsedMensaSignupSchema,
      nullable: true,
    },
  },
  required: ['id', 'name'],
};
