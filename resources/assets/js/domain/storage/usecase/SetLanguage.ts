import {Language} from '../../common/model/Language';
import {StorageRepository} from '../repository/StorageRepository';
import {inject, injectable} from 'tsyringe';
import {TypeSymbols} from "../../../di/TypeSymbols";

@injectable()
export class SetLanguage {
  constructor(
    @inject(TypeSymbols.StorageRepository)
    private readonly repository: StorageRepository
  ) {}

  execute(language: Language): void {
    return this.repository.setLanguage(language);
  }
}
