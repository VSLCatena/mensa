import {
  StorageRepository,
  StorageRepositoryToken,
} from '../repository/StorageRepository';
import {inject, injectable} from 'tsyringe';

@injectable()
export class SetUserToken {
  constructor(
    @inject(StorageRepositoryToken)
    private readonly repository: StorageRepository
  ) {}

  execute(token: string | null): void {
    this.repository.setUserToken(token);
  }
}
