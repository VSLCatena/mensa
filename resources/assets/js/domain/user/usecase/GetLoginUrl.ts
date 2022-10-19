import {
  UserRepository,
  UserRepositoryToken,
} from '../repository/UserRepository';
import {inject, injectable} from 'tsyringe';

@injectable()
export class GetLoginUrl {
  constructor(
    @inject(UserRepositoryToken)
    private readonly repository: UserRepository
  ) {}

  async execute(): Promise<string> {
    return await this.repository.getUrl();
  }
}
