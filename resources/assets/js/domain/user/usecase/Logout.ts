import {SetUserToken} from '../../storage/usecase/SetUserToken';
import {inject, injectable} from 'tsyringe';
import {UserRepositoryToken} from '../repository/UserRepository';

@injectable()
export class Logout {
  constructor(
    @inject(UserRepositoryToken)
    private readonly setUserToken: SetUserToken
  ) {}

  async execute(): Promise<void> {
    return this.setUserToken.execute(null);
  }
}
