import {DIModule} from '../../di/DIModule';
import {DependencyContainer} from 'tsyringe';
import {UserDataRepository} from '../repository/UserDataRepository';
import {UserRepositoryToken} from '../../../domain/user/repository/UserRepository';

export class UserModule extends DIModule {
  override register(container: DependencyContainer) {
    container.register(UserRepositoryToken, {useClass: UserDataRepository});
  }
}
