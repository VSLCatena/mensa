import {MensaList} from '../model/MensaList';
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
export class GetMensas {
  constructor(
    @inject(MensaRepositoryToken)
    private readonly repository: MensaRepository,
    private readonly withAuthentication: WithAuthentication
  ) {}

  async execute(weekOffset: number): Promise<MensaList> {
    return await this.withAuthentication.call(
      async token => await this.repository.getMensas(weekOffset, token),
      Strategy.AUTH_OPTIONAL
    );
  }
}
