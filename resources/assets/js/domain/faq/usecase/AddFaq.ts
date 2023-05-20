import {NewFaq} from '../model/Faq';
import {inject, injectable} from 'tsyringe';
import {FaqRepository} from '../repository/FaqRepository';
import {TypeSymbols} from "../../../di/TypeSymbols";
import {Strategy, WithAuthentication} from "../../common/usecase/WithAuthentication";

@injectable()
export class AddFaq {
  constructor(
    @inject(TypeSymbols.FaqRepository)
    private readonly repository: FaqRepository,
    private readonly withAuthentication: WithAuthentication
  ) {}

  async execute(faq: NewFaq): Promise<void> {
    return await this.withAuthentication.call(
      async token => await this.repository.addFaq(token, faq),
      Strategy.AUTH_REQUIRED
    );
  }
}
