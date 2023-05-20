import {StorageRepository} from '../repository/StorageRepository';
import {inject, injectable} from 'tsyringe';
import {TypeSymbols} from "../../../di/TypeSymbols";

@injectable()
export class GetUserToken {
  constructor(
    @inject(TypeSymbols.StorageRepository)
    private readonly repository: StorageRepository
  ) {}

  execute(): string | undefined {
    return this.repository.getUserToken();
  }
}
