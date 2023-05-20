import {IdentifiableUser} from "../../common/model/User";
import {MensaSignup} from "../../signup/model/MensaSignup";

export interface SignupUser extends IdentifiableUser {
  signup?: MensaSignup;
}