import {DIModule} from '../../di/DIModule';
import {DependencyContainer} from 'tsyringe';
import {MensaDataRepository} from '../repository/MensaDataRepository';
import {MensaRepositoryToken} from '../../../domain/mensa/repository/MensaRepository';

export class MensaModule extends DIModule {
  override register(container: DependencyContainer) {
    container.register(MensaRepositoryToken, {useClass: MensaDataRepository});
  }
}
