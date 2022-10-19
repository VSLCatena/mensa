import {
  StorageRepository,
  StorageRepositoryToken,
} from '../repository/StorageRepository';
import {inject, injectable} from 'tsyringe';

@injectable()
export class GetUserToken {
  constructor(
    @inject(StorageRepositoryToken)
    private readonly repository: StorageRepository
  ) {}

  execute(): string | undefined {
    return this.repository.getUserToken();
  }
}
