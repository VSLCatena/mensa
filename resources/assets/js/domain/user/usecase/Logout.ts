import {SetUserToken} from '../../storage/usecase/SetUserToken';
import {injectable} from 'tsyringe';

@injectable()
export class Logout {
  constructor(
    private readonly setUserToken: SetUserToken
  ) {}

  async execute(): Promise<void> {
    return this.setUserToken.execute(null);
  }
}
