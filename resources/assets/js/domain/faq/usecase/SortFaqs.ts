import {inject, injectable} from 'tsyringe';
import {FaqRepository} from '../repository/FaqRepository';
import {TypeSymbols} from "../../../di/TypeSymbols";
import {Strategy, WithAuthentication} from "../../common/usecase/WithAuthentication";

@injectable()
export class SortFaqs {
  constructor(
    @inject(TypeSymbols.FaqRepository)
    private readonly repository: FaqRepository,
    private readonly withAuthentication: WithAuthentication
  ) {}

  async execute(ids: string[]): Promise<void> {
    return await this.withAuthentication.call(
      async token => await this.repository.sortFaqs(token, ids),
      Strategy.AUTH_REQUIRED
    );
  }
}
