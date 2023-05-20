import {
  WithAuthentication,
  Strategy,
} from '../../common/usecase/WithAuthentication';
import {MensaRepository} from '../repository/MensaRepository';
import {inject, injectable} from 'tsyringe';
import {TypeSymbols} from "../../../di/TypeSymbols";

@injectable()
export class DeleteMensa {
  constructor(
    @inject(TypeSymbols.MensaRepository)
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
