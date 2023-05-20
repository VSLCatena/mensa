import {StorageRepository} from '../repository/StorageRepository';
import {inject, injectable} from 'tsyringe';
import {TypeSymbols} from "../../../di/TypeSymbols";

@injectable()
export class SetUserToken {
  constructor(
    @inject(TypeSymbols.StorageRepository)
    private readonly repository: StorageRepository
  ) {}

  execute(token: string | null): void {
    this.repository.setUserToken(token);
  }
}
