import {Faq} from '../model/Faq';
import {inject, injectable} from 'tsyringe';
import {FaqRepository} from '../repository/FaqRepository';
import {TypeSymbols} from "../../../di/TypeSymbols";
import {Strategy, WithAuthentication} from "../../common/usecase/WithAuthentication";

@injectable()
export class EditFaq {
  constructor(
    @inject(TypeSymbols.FaqRepository)
    private readonly repository: FaqRepository,
    private readonly withAuthentication: WithAuthentication
  ) {}

  async execute(faq: Faq): Promise<void> {
    return await this.withAuthentication.call(
      async token => await this.repository.editFaq(token, faq),
      Strategy.AUTH_REQUIRED
    );
  }
}
