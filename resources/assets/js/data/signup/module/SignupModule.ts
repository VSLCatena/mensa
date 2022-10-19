import {DIModule} from '../../di/DIModule';
import {DependencyContainer} from 'tsyringe';
import {SignupDataRepository} from '../repository/SignupDataRepository';
import {SignupRepositoryToken} from '../../../domain/signup/repository/SignupRepository';

export class SignupModule extends DIModule {
  override register(container: DependencyContainer) {
    container.register(SignupRepositoryToken, {useClass: SignupDataRepository});
  }
}
