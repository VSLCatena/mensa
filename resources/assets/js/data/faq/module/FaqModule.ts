import {DIModule} from '../../di/DIModule';
import {DependencyContainer} from 'tsyringe';
import {FaqDataRepository} from '../repository/FaqDataRepository';
import {FaqRepositoryToken} from '../../../domain/faq/repository/FaqRepository';

export class FaqModule extends DIModule {
  override register(container: DependencyContainer) {
    container.register(FaqRepositoryToken, {useClass: FaqDataRepository});
  }
}
