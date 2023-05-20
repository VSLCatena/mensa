import {UserRepository} from '../repository/UserRepository';
import {UserPreferences} from '../../common/model/User';
import {
  WithAuthentication,
  Strategy,
} from '../../common/usecase/WithAuthentication';
import {inject, injectable} from 'tsyringe';
import {TypeSymbols} from "../../../di/TypeSymbols";

@injectable()
export class UpdateSelf {
  constructor(
    @inject(TypeSymbols.UserRepository)
    private readonly repository: UserRepository,
    private readonly withAuthentication: WithAuthentication
  ) {}

  async execute(user: UserPreferences): Promise<void> {
    return await this.withAuthentication.call(
      async token => await this.repository.updateSelf(token, user),
      Strategy.AUTH_REQUIRED
    );
  }
}
