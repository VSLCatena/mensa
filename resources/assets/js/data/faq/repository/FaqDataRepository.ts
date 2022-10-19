import {FaqRepository} from '../../../domain/faq/repository/FaqRepository';
import {Faq} from '../../../domain/faq/model/Faq';
import axios from 'axios';
import {Config} from '../../../Config';
import {singleton} from 'tsyringe';
import {ResponseMapper} from '../../common/mapper/ResponseMapper';
import {SchemaMapper} from '../../common/mapper/SchemaMapper';
import {FaqListSchema} from '../schema/FaqListSchema';

@singleton()
export class FaqDataRepository implements FaqRepository {
  constructor(
    private readonly responseMapper: ResponseMapper,
    private readonly schemaMapper: SchemaMapper
  ) {}

  async getFaqs(): Promise<Faq[]> {
    return await axios
      .get(`${Config.apiBaseUrl}/faqs`)
      .then(value => this.responseMapper.map(value))
      .then(value => this.schemaMapper.map(FaqListSchema, value));
  }

  async addFaq(_faq: Faq): Promise<void> {
    // TODO
  }
}
