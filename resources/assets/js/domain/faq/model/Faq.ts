export interface Faq {
  id: string;
  question: string;
  answer: string;
}

export type NewFaq = Omit<Faq, 'id'>;
