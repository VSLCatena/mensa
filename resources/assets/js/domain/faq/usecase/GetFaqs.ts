import repository from "../repository/FaqRepository";
import Faq from "../model/Faq";

export default async function GetFaqs(): Promise<Faq[]> {
    return repository.getFaqs()
}