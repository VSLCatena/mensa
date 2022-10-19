import {Language} from '../../common/model/Language';
import {
  StorageRepository,
  StorageRepositoryToken,
} from '../repository/StorageRepository';
import {inject, injectable} from 'tsyringe';

@injectable()
export class SetLanguage {
  constructor(
    @inject(StorageRepositoryToken)
    private readonly repository: StorageRepository
  ) {}

  execute(language: Language): void {
    return this.repository.setLanguage(language);
  }
}
