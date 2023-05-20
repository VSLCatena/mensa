import {FaqRepository} from '../../../domain/faq/repository/FaqRepository';
import {Faq, NewFaq} from '../../../domain/faq/model/Faq';
import axios from 'axios';
import {Config} from '../../../Config';
import {singleton} from 'tsyringe';
import {ResponseMapper} from '../../common/mapper/ResponseMapper';
import {SchemaMapper} from '../../common/mapper/SchemaMapper';
import {FaqListSchema} from '../schema/FaqListSchema';
import {OptionalAuthHeader, WithAuthHeader} from "../../utils/WithAuthHeader";

@singleton()
export class FaqDataRepository implements FaqRepository {
  constructor(
    private readonly responseMapper: ResponseMapper,
    private readonly schemaMapper: SchemaMapper
  ) {}

  async getFaqs(authToken: string | null): Promise<Faq[]> {
    return await axios
      .get(`${Config.apiBaseUrl}/faqs`, {
        headers: OptionalAuthHeader(authToken),
      })
      .then(value => this.responseMapper.map(value))
      .then(value => this.schemaMapper.map(FaqListSchema, value));
  }

  async addFaq(authToken: string, faq: NewFaq): Promise<void> {
    return await axios
      .post(`${Config.apiBaseUrl}/faq/new`, faq, {
        headers: WithAuthHeader(authToken),
      })
      .then(value => this.responseMapper.map(value));
  }

  async deleteFaq(authToken: string, faq: Faq): Promise<void> {
    return await axios
      .delete(`${Config.apiBaseUrl}/faq/${faq.id}`, {
        headers: WithAuthHeader(authToken),
      })
      .then(value => this.responseMapper.map(value));
  }

  async editFaq(authToken: string, faq: Faq): Promise<void> {
    return await axios
      .put(`${Config.apiBaseUrl}/faq/${faq.id}`, faq, {
        headers: WithAuthHeader(authToken),
      })
      .then(value => this.responseMapper.map(value));
  }

  async sortFaqs(authToken: string, ids: string[]): Promise<void> {
    return await axios
      .post(`${Config.apiBaseUrl}/faqs/sort`, { ids }, {
        headers: WithAuthHeader(authToken),
      })
      .then(value => this.responseMapper.map(value));
  }
}
