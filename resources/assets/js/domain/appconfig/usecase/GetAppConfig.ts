import {AppConfig} from '../model/AppConfig';
import {inject, injectable} from 'tsyringe';
import {AppConfigRepository} from '../repository/AppConfigRepository';
import {TypeSymbols} from "../../../di/TypeSymbols";

@injectable()
export class GetAppConfig {
  constructor(
    @inject(TypeSymbols.AppConfigRepository)
    private readonly repository: AppConfigRepository
  ) {}

  async execute(): Promise<AppConfig> {
    return await this.repository.getAppConfig();
  }
}
