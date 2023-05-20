import {MensaSignup} from '../model/MensaSignup';
import {Mensa} from '../../mensa/model/Mensa';

export interface SignupRepository {
  getSignup: (
    mensaId: string,
    authToken: string | null
  ) => Promise<MensaSignup>;

  signup: (
    mensa: Mensa,
    email: string,
    signups: MensaSignup[],
    authToken: string | null
  ) => Promise<void>;

  editSignup: (
    mensa: Mensa,
    email: string,
    signups: MensaSignup[],
    authToken: string | null
  ) => Promise<void>;

  signout: (
    mensaId: string,
    signupId: string,
    confirmationCode: string,
    authToken: string | null
  ) => Promise<void>;
}
