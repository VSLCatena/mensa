import {AppConfig} from '../../../domain/appconfig/model/AppConfig';
import axios from 'axios';
import {Config} from '../../../Config';
import {singleton} from 'tsyringe';
import {AppConfigSchema} from '../schema/AppConfigSchema';
import {ResponseMapper} from '../../common/mapper/ResponseMapper';
import {SchemaMapper} from '../../common/mapper/SchemaMapper';

@singleton()
export class AppConfigCache {
  constructor(
    private readonly responseMapper: ResponseMapper,
    private readonly schemaMapper: SchemaMapper
  ) {}

  private cachedAppConfig: AppConfig | null = null;

  async getAppConfig(): Promise<AppConfig> {
    if (this.cachedAppConfig !== null) return this.cachedAppConfig;

    try {
      const response = await this.doAppConfigCall();
      this.cachedAppConfig = response;
      return response;
    } catch (e) {
      return await Promise.reject(e);
    }
  }

  private async doAppConfigCall(): Promise<AppConfig> {
    return await axios
      .get(`${Config.apiBaseUrl}/appconfig`)
      .then(value => this.responseMapper.map(value))
      .then(value => this.schemaMapper.map(AppConfigSchema, value));
  }
}
