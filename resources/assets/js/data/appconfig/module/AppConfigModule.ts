import {DIModule} from '../../di/DIModule';
import {DependencyContainer} from 'tsyringe';
import {AppConfigRepositoryToken} from '../../../domain/appconfig/repository/AppConfigRepository';
import {AppConfigDataRepository} from '../repository/AppConfigDataRepository';

export class AppConfigModule extends DIModule {
  constructor() {
    super();
  }

  override register(container: DependencyContainer) {
    container.register(AppConfigRepositoryToken, {
      useClass: AppConfigDataRepository,
    });
  }
}
