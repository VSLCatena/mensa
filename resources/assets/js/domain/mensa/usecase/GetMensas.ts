import {MensaList} from '../model/MensaList';
import {
  WithAuthentication,
  Strategy,
} from '../../common/usecase/WithAuthentication';
import {MensaRepository} from '../repository/MensaRepository';
import {inject, injectable} from 'tsyringe';
import {TypeSymbols} from "../../../di/TypeSymbols";

@injectable()
export class GetMensas {
  constructor(
    @inject(TypeSymbols.MensaRepository)
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
