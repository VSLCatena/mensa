import {MensaSignup} from '../model/MensaSignup';
import {Mensa} from '../../mensa/model/Mensa';
import {
  WithAuthentication,
  Strategy,
} from '../../common/usecase/WithAuthentication';
import {SignupRepository} from '../repository/SignupRepository';
import {inject, injectable} from 'tsyringe';
import {TypeSymbols} from "../../../di/TypeSymbols";

@injectable()
export class SignupMensa {
  constructor(
    @inject(TypeSymbols.SignupRepository)
    private readonly repository: SignupRepository,
    private readonly withAuthentication: WithAuthentication
  ) {}

  async execute(
    mensa: Mensa,
    email: string,
    signups: MensaSignup[]
  ): Promise<void> {
    return await this.withAuthentication.call(
      async token => await this.repository.signup(mensa, email, signups, token),
      Strategy.AUTH_OPTIONAL
    );
  }
}
