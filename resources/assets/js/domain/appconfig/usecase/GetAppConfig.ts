import {AppConfig} from '../model/AppConfig';
import {inject, injectable} from 'tsyringe';
import {
  AppConfigRepository,
  AppConfigRepositoryToken,
} from '../repository/AppConfigRepository';

@injectable()
export class GetAppConfig {
  constructor(
    @inject(AppConfigRepositoryToken)
    private readonly repository: AppConfigRepository
  ) {}

  async execute(): Promise<AppConfig> {
    return await this.repository.getAppConfig();
  }
}
