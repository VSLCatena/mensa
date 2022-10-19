import {Faq} from '../model/Faq';

export interface FaqRepository {
  getFaqs: () => Promise<Faq[]>;

  addFaq: (faq: Faq) => Promise<void>;
}

export const FaqRepositoryToken = 'FaqRepositoryToken';
