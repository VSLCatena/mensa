import {
  WithAuthentication,
  Strategy,
} from '../../common/usecase/WithAuthentication';
import {EditMensa} from '../model/EditMensa';
import {MensaRepository} from '../repository/MensaRepository';
import {inject, injectable} from 'tsyringe';
import {TypeSymbols} from "../../../di/TypeSymbols";

@injectable()
export class UpdateMensa {
  constructor(
    @inject(TypeSymbols.MensaRepository)
    private readonly repository: MensaRepository,
    private readonly withAuthentication: WithAuthentication
  ) {}

  async execute(mensa: EditMensa): Promise<void> {
    return await this.withAuthentication.call(
      async token => await this.repository.editMensa(mensa, token),
      Strategy.AUTH_REQUIRED
    );
  }
}
