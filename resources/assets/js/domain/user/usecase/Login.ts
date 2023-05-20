import {SetUserToken} from '../../storage/usecase/SetUserToken';
import {UserRepository} from '../repository/UserRepository';
import {inject, injectable} from 'tsyringe';
import {TypeSymbols} from "../../../di/TypeSymbols";

@injectable()
export class Login {
  constructor(
    @inject(TypeSymbols.UserRepository)
    private readonly repository: UserRepository,
    private readonly setUserToken: SetUserToken
  ) {}

  async execute(token: string): Promise<void> {
    const code = await this.repository.exchangeToken(token);
    return this.setUserToken.execute(code);
  }
}
