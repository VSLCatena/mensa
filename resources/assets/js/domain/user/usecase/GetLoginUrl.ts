import {UserRepository} from '../repository/UserRepository';
import {inject, injectable} from 'tsyringe';
import {TypeSymbols} from "../../../di/TypeSymbols";

@injectable()
export class GetLoginUrl {
  constructor(
    @inject(TypeSymbols.UserRepository)
    private readonly repository: UserRepository
  ) {}

  async execute(): Promise<string> {
    return await this.repository.getUrl();
  }
}
