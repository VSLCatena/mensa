import {Mensa} from '../model/Mensa';
import {MensaRepository} from '../repository/MensaRepository';
import {inject, injectable} from 'tsyringe';
import {TypeSymbols} from "../../../di/TypeSymbols";

@injectable()
export class GetMensa {
  constructor(
    @inject(TypeSymbols.MensaRepository)
    private readonly repository: MensaRepository
  ) {}

  async execute(mensaId: string): Promise<Mensa | null> {
    return await this.repository.getMensa(mensaId);
  }
}
