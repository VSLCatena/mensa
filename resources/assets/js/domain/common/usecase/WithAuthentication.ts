import {GetUserToken} from '../../storage/usecase/GetUserToken';
import {AuthenticationTokenMissingError} from '../errors/AuthenticationTokenMissingError';
import {injectable} from 'tsyringe';

@injectable()
export class WithAuthentication {
  constructor(private readonly getUserToken: GetUserToken) {}

  async call<T, B extends Strategy>(
    call: (
      token: B extends Strategy.AUTH_REQUIRED ? string : string | null
    ) => Promise<T>,
    strategy: B
  ): Promise<T> {
    const token = this.getUserToken.execute() ?? null;
    if (token === null && strategy === Strategy.AUTH_REQUIRED) {
      throw new AuthenticationTokenMissingError();
    }

    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    return await call(token as any);
  }
}

export enum Strategy {
  AUTH_REQUIRED,
  AUTH_OPTIONAL,
}
