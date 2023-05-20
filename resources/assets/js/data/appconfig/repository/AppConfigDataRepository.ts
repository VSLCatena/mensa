import {AppConfigRepository} from '../../../domain/appconfig/repository/AppConfigRepository';
import {AppConfig} from '../../../domain/appconfig/model/AppConfig';
import {singleton} from 'tsyringe';
import {AppConfigCache} from '../cache/AppConfigCache';

@singleton()
export class AppConfigDataRepository implements AppConfigRepository {
  constructor(private readonly appConfigCache: AppConfigCache) {}

  async getAppConfig(): Promise<AppConfig> {
    return await this.appConfigCache.getAppConfig();
  }
}
