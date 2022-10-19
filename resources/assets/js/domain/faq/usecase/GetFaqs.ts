import {Faq} from '../model/Faq';
import {inject, injectable} from 'tsyringe';
import {FaqRepository, FaqRepositoryToken} from '../repository/FaqRepository';

@injectable()
export class GetFaqs {
  constructor(
    @inject(FaqRepositoryToken)
    private readonly repository: FaqRepository
  ) {}

  async execute(): Promise<Faq[]> {
    return await this.repository.getFaqs();
  }
}
