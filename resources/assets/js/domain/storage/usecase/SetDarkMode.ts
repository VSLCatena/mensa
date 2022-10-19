import {
  StorageRepository,
  StorageRepositoryToken,
} from '../repository/StorageRepository';
import {inject, injectable} from 'tsyringe';

@injectable()
export class SetDarkMode {
  constructor(
    @inject(StorageRepositoryToken)
    private readonly repository: StorageRepository
  ) {}

  execute(mode: boolean): void {
    this.repository.setDarkMode(mode);
  }
}
