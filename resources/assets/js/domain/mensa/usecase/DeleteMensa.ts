import {
  WithAuthentication,
  Strategy,
} from '../../common/usecase/WithAuthentication';
import {
  MensaRepository,
  MensaRepositoryToken,
} from '../repository/MensaRepository';
import {inject, injectable} from 'tsyringe';

@injectable()
export class DeleteMensa {
  constructor(
    @inject(MensaRepositoryToken)
    private readonly repository: MensaRepository,
    private readonly withAuthentication: WithAuthentication
  ) {}

  async execute(mensaId: string): Promise<void> {
    return await this.withAuthentication.call(
      async token => await this.repository.deleteMensa(mensaId, token),
      Strategy.AUTH_REQUIRED
    );
  }
}
