import {Faq} from '../model/Faq';
import {inject, injectable} from 'tsyringe';
import {FaqRepository} from '../repository/FaqRepository';
import {TypeSymbols} from "../../../di/TypeSymbols";
import {Strategy, WithAuthentication} from "../../common/usecase/WithAuthentication";

@injectable()
export class GetFaqs {
  constructor(
    @inject(TypeSymbols.FaqRepository)
    private readonly repository: FaqRepository,
    private readonly withAuthentication: WithAuthentication
  ) {}

  async execute(): Promise<Faq[]> {
    return await this.withAuthentication.call(
      async token => await this.repository.getFaqs(token),
      Strategy.AUTH_OPTIONAL
    );
  }
}
