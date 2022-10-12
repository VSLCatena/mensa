import repository from '../../../data/faq/repository/FaqRepositoryImpl';
import Faq from "../model/Faq";

export interface FaqRepository {
    getFaqs(): Promise<Faq[]>

    addFaq(faq: Faq): Promise<void>
}

export default repository;