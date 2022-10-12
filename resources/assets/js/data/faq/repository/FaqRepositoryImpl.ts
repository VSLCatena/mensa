import { FaqRepository } from "../../../domain/faq/repository/FaqRepository";
import Faq from "../../../domain/faq/model/Faq";
import axios from "axios";
import Config from "../../../Config";
import {OptionalAuthHeader} from "../../utils/WithAuthHeader";
import MapResponse from "../../utils/MapResponse";
import MapFaqs from "../mapper/MapFaqs";


class FaqRepositoryImpl implements FaqRepository {
    getFaqs(): Promise<Faq[]> {
        return axios.get(`${Config.API_BASE_URL}/faqs`)
            .then(MapResponse)
            .then(value => MapFaqs(value).asPromise());
    }

    async addFaq(faq: Faq): Promise<void> {
        // TODO
        return;
    }
}

const repository: FaqRepository = new FaqRepositoryImpl();
export default repository;