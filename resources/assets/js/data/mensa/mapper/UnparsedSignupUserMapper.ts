import {injectable} from "tsyringe";
import {SignupUser} from "../../../domain/mensa/model/SignupUser";
import {UnparsedSignupUser} from "../schema/UnparsedSignupUserSchema";
import {UnparsedMensaSignupMapper} from "../../signup/mapper/UnparsedMensaSignupMapper";


@injectable()
export class UnparsedSignupUserMapper {

  constructor(
    private readonly signupMapper: UnparsedMensaSignupMapper
  ) {
  }
  map(data: UnparsedSignupUser): SignupUser {
    return {
      ...data,
      signup: data.signup ? this.signupMapper.map(data.signup) : undefined,
    };
  }
}