import {Faq, NewFaq} from '../model/Faq';

export interface FaqRepository {
  getFaqs: (authToken: string | null) => Promise<Faq[]>;

  addFaq: (authToken: string, faq: NewFaq) => Promise<void>;

  editFaq: (authToken: string, faq: Faq) => Promise<void>;

  sortFaqs: (authToken: string, ids: string[]) => Promise<void>;

  deleteFaq: (authToken: string, faq: Faq) => Promise<void>;
}
