import {Language} from '../../common/model/Language';
import {StorageRepository} from '../repository/StorageRepository';
import {inject, injectable} from 'tsyringe';
import {TypeSymbols} from "../../../di/TypeSymbols";

@injectable()
export class GetLanguage {
  constructor(
    @inject(TypeSymbols.StorageRepository)
    private readonly repository: StorageRepository
  ) {}

  execute(): Language {
    return this.repository.getLanguage();
  }
}
