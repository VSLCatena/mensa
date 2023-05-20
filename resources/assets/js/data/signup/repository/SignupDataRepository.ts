import {Mensa} from '../../../domain/mensa/model/Mensa';
import {MensaSignup} from '../../../domain/signup/model/MensaSignup';
import {Config} from '../../../Config';
import axios from 'axios';
import {OptionalAuthHeader} from '../../utils/WithAuthHeader';
import {SignupRepository} from '../../../domain/signup/repository/SignupRepository';
import {singleton} from 'tsyringe';
import {ResponseMapper} from '../../common/mapper/ResponseMapper';
import {UnparsedMensaSignupMapper} from '../mapper/UnparsedMensaSignupMapper';
import {SchemaMapper} from '../../common/mapper/SchemaMapper';
import {UnparsedMensaSignup, UnparsedMensaSignupSchema} from '../schema/UnparsedMensaSignupSchema';

@singleton()
export class SignupDataRepository implements SignupRepository {
  constructor(
    private readonly responseMapper: ResponseMapper,
    private readonly schemaMapper: SchemaMapper,
    private readonly mensaSignupMapper: UnparsedMensaSignupMapper
  ) {}

  async getSignup(
    mensaId: string,
    authToken: string | null
  ): Promise<MensaSignup> {
    return await axios
      .get(`${Config.apiBaseUrl}/mensa/${mensaId}/signup`, {
        headers: OptionalAuthHeader(authToken),
      })
      .then(value => this.responseMapper.map(value))
      .then(value =>
        this.schemaMapper.map<UnparsedMensaSignup>(
          UnparsedMensaSignupSchema,
          value
        )
      )
      .then(value => this.mensaSignupMapper.map(value));
  }

  async signup(
    mensa: Mensa,
    email: string,
    signups: MensaSignup[],
    authToken: string | null
  ): Promise<void> {
    return await axios
      .post(
        `${Config.apiBaseUrl}/mensa/${mensa.id}/signup`,
        {
          email,
          signups,
        },
        {headers: OptionalAuthHeader(authToken)}
      )
      .then(value => this.responseMapper.map(value));
  }

  async editSignup(
    mensa: Mensa,
    email: string,
    signups: MensaSignup[],
    authToken: string | null
  ): Promise<void> {
    return await axios
      .put(
        `${Config.apiBaseUrl}/mensa/${mensa.id}/signup`,
        {
          email,
          signups,
        },
        {headers: OptionalAuthHeader(authToken)}
      )
      .then(value => this.responseMapper.map(value));
  }

  async signout(
    mensaId: string,
    signupId: string,
    confirmationCode: string,
    authToken: string | null
  ): Promise<void> {
    return await axios
      .delete(`${Config.apiBaseUrl}/mensa/${mensaId}/signup/${signupId}`, {
        headers: OptionalAuthHeader(authToken),
        data: {confirmation_code: confirmationCode},
      })
      .then(value => this.responseMapper.map(value));
  }
}
