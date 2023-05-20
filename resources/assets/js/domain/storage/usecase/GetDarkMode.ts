import {StorageRepository} from '../repository/StorageRepository';
import {inject, injectable} from 'tsyringe';
import {TypeSymbols} from "../../../di/TypeSymbols";

@injectable()
export class GetDarkMode {
  constructor(
    @inject(TypeSymbols.StorageRepository)
    private readonly repository: StorageRepository
  ) {}

  execute(): boolean {
    return this.repository.getDarkMode();
  }
}
