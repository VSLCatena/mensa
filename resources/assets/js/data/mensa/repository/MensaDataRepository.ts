import {Mensa} from '../../../domain/mensa/model/Mensa';
import {MensaRepository} from '../../../domain/mensa/repository/MensaRepository';
import {Config} from '../../../Config';
import axios from 'axios';
import {MensaList} from '../../../domain/mensa/model/MensaList';
import {WithAuthHeader, OptionalAuthHeader} from '../../utils/WithAuthHeader';
import {singleton} from 'tsyringe';
import {ResponseMapper} from '../../common/mapper/ResponseMapper';
import {SchemaMapper} from '../../common/mapper/SchemaMapper';
import {UnparsedMensaMapper} from '../mapper/UnparsedMensaMapper';
import {MensaListSchema, UnparsedMensaList} from '../schema/MensaListSchema';
import {EditMensa} from '../../../domain/mensa/model/EditMensa';

@singleton()
export class MensaDataRepository implements MensaRepository {
  constructor(
    private readonly responseMapper: ResponseMapper,
    private readonly schemaMapper: SchemaMapper,
    private readonly unparsedMensaMapper: UnparsedMensaMapper
  ) {}

  async getMensas(
    weekOffset: number | null,
    authToken: string | null
  ): Promise<MensaList> {
    return await axios
      .get(`${Config.apiBaseUrl}/mensas`, {
        headers: OptionalAuthHeader(authToken),
        params: {weekOffset},
      })
      .then(value => this.responseMapper.map(value))
      .then(value =>
        this.schemaMapper.map<UnparsedMensaList>(MensaListSchema, value)
      )
      .then(value => this.unparsedMensaMapper.mapList(value));
  }

  getMensa(_mensaId: string): Promise<Mensa> {
    throw new Error('Method not implemented.');
  }

  async addMensa(mensa: EditMensa, authToken: string): Promise<void> {
    return await axios
      .post(`${Config.apiBaseUrl}/mensa/new`, mensa, {
        headers: WithAuthHeader(authToken),
      })
      .then(value => this.responseMapper.map(value))
      .then(); // TODO
  }

  async editMensa(mensa: EditMensa, authToken: string): Promise<void> {
    const mensaId = mensa.id;
    if (mensaId === undefined) {
      throw Error('ID not found in mensa');
    }

    return await axios
      .patch(`${Config.apiBaseUrl}/mensa/${mensaId}`, mensa, {
        headers: WithAuthHeader(authToken),
      })
      .then(value => this.responseMapper.map(value))
      .then(); // TODO
  }

  async deleteMensa(mensaId: string, authToken: string): Promise<void> {
    return await axios
      .delete(`${Config.apiBaseUrl}/mensa/${mensaId}`, {
        headers: WithAuthHeader(authToken),
      })
      .then(value => this.responseMapper.map(value));
  }
}
