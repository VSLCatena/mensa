import {UserRepository} from '../repository/UserRepository';
import {AnonymousUser, AuthUser} from '../../common/model/User';
import {
  WithAuthentication,
  Strategy,
} from '../../common/usecase/WithAuthentication';
import {inject, injectable} from 'tsyringe';
import {TypeSymbols} from "../../../di/TypeSymbols";

@injectable()
export class GetSelf {
  constructor(
    @inject(TypeSymbols.UserRepository)
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
