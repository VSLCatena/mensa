import {AppConfig} from '../model/AppConfig';

export interface AppConfigRepository {
  getAppConfig: () => Promise<AppConfig>;
}

export const AppConfigRepositoryToken = 'AppConfigRepositoryToken';
