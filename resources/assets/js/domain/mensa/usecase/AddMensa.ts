import {
  WithAuthentication,
  Strategy,
} from '../../common/usecase/WithAuthentication';
import {EditMensa} from '../model/EditMensa';
import {
  MensaRepository,
  MensaRepositoryToken,
} from '../repository/MensaRepository';
import {inject, injectable} from 'tsyringe';

@injectable()
export class AddMensa {
  constructor(
    @inject(MensaRepositoryToken)
    private readonly repository: MensaRepository,
    private readonly withAuthentication: WithAuthentication
  ) {}

  async execute(mensa: EditMensa): Promise<void> {
    return await this.withAuthentication.call(
      async token => await this.repository.addMensa(mensa, token),
      Strategy.AUTH_REQUIRED
    );
  }
}
