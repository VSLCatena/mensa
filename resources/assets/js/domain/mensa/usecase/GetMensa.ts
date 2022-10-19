import {Mensa} from '../model/Mensa';
import {
  MensaRepository,
  MensaRepositoryToken,
} from '../repository/MensaRepository';
import {inject, injectable} from 'tsyringe';

@injectable()
export class GetMensa {
  constructor(
    @inject(MensaRepositoryToken)
    private readonly repository: MensaRepository
  ) {}

  async execute(mensaId: string): Promise<Mensa | null> {
    return await this.repository.getMensa(mensaId);
  }
}
