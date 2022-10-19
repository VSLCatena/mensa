import {MensaSignup} from '../model/MensaSignup';
import {Mensa} from '../../mensa/model/Mensa';
import {
  WithAuthentication,
  Strategy,
} from '../../common/usecase/WithAuthentication';
import {
  SignupRepository,
  SignupRepositoryToken,
} from '../repository/SignupRepository';
import {inject, injectable} from 'tsyringe';

@injectable()
export class UpdateMensaSignup {
  constructor(
    @inject(SignupRepositoryToken)
    private readonly repository: SignupRepository,
    private readonly withAuthentication: WithAuthentication
  ) {}

  async execute(
    mensa: Mensa,
    email: string,
    signups: MensaSignup[]
  ): Promise<void> {
    return await this.withAuthentication.call(
      async token =>
        await this.repository.editSignup(mensa, email, signups, token),
      Strategy.AUTH_OPTIONAL
    );
  }
}
