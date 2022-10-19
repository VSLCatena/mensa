import {SetUserToken} from '../../storage/usecase/SetUserToken';
import {
  UserRepository,
  UserRepositoryToken,
} from '../repository/UserRepository';
import {inject, injectable} from 'tsyringe';

@injectable()
export class Login {
  constructor(
    @inject(UserRepositoryToken)
    private readonly repository: UserRepository,
    private readonly setUserToken: SetUserToken
  ) {}

  async execute(token: string): Promise<void> {
    const code = await this.repository.exchangeToken(token);
    return this.setUserToken.execute(code);
  }
}
