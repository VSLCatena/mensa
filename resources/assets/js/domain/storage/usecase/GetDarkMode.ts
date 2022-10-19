import {
  StorageRepository,
  StorageRepositoryToken,
} from '../repository/StorageRepository';
import {inject, injectable} from 'tsyringe';

@injectable()
export class GetDarkMode {
  constructor(
    @inject(StorageRepositoryToken)
    private readonly repository: StorageRepository
  ) {}

  execute(): boolean {
    return this.repository.getDarkMode();
  }
}
