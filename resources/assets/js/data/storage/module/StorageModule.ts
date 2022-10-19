import {DIModule} from '../../di/DIModule';
import {DependencyContainer} from 'tsyringe';
import {StorageDataRepository} from '../repository/StorageDataRepository';
import {StorageRepositoryToken} from '../../../domain/storage/repository/StorageRepository';

export class StorageModule extends DIModule {
  override register(container: DependencyContainer) {
    container.register(StorageRepositoryToken, {
      useClass: StorageDataRepository,
    });
  }
}
