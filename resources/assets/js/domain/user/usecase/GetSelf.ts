import {
  UserRepository,
  UserRepositoryToken,
} from '../repository/UserRepository';
import {AnonymousUser, AuthUser} from '../../common/model/User';
import {
  WithAuthentication,
  Strategy,
} from '../../common/usecase/WithAuthentication';
import {inject, injectable} from 'tsyringe';

@injectable()
export class GetSelf {
  constructor(
    @inject(UserRepositoryToken)
    private readonly repository: UserRepository,
    private readonly withAuthentication: WithAuthentication
  ) {}

  async execute(): Promise<AuthUser> {
    try {
      return await this.withAuthentication.call(
        async token => await this.repository.getSelf(token),
        Strategy.AUTH_REQUIRED
      );
    } catch (e) {
      return AnonymousUser;
    }
  }
}
