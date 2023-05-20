import {UserRepository} from '../../../domain/user/repository/UserRepository';
import axios from 'axios';
import {Config} from '../../../Config';
import {FullUser, UserPreferences} from '../../../domain/common/model/User';
import {WithAuthHeader} from '../../utils/WithAuthHeader';
import {singleton} from 'tsyringe';
import {ResponseMapper} from '../../common/mapper/ResponseMapper';
import {SchemaMapper} from '../../common/mapper/SchemaMapper';
import {UnparsedFullUserMapper} from '../mapper/UnparsedFullUserMapper';
import {
  UnparsedFullUser,
  UnparsedFullUserSchema,
} from '../schema/UnparsedFullUserSchema';
import {AuthorizationTokenSchema} from '../schema/AuthorizationTokenSchema';
import {AuthorizationUriSchema} from '../schema/AuthorizationUriSchema';
import {AuthorizationToken} from '../model/AuthorizationToken';
import {AuthorizationUri} from '../model/AuthorizationUri';

@singleton()
export class UserDataRepository implements UserRepository {
  constructor(
    private readonly responseMapper: ResponseMapper,
    private readonly schemaMapper: SchemaMapper,
    private readonly unparsedFullUserMapper: UnparsedFullUserMapper
  ) {}

  async getSelf(authToken: string): Promise<FullUser> {
    return await axios
      .get(`${Config.apiBaseUrl}/user/self`, {
        headers: WithAuthHeader(authToken),
      })
      .then(value => this.responseMapper.map(value))
      .then(value =>
        this.schemaMapper.map<UnparsedFullUser>(UnparsedFullUserSchema, value)
      )
      .then(value => this.unparsedFullUserMapper.map(value));
  }

  async updateSelf(authToken: string, user: UserPreferences): Promise<void> {
    let params = {};

    if (user.allergies !== undefined)
      params = {...params, allergies: user.allergies};
    if (user.extraInfo !== undefined)
      params = {...params, extraInfo: user.extraInfo};
    if (user.foodPreference !== undefined) {
      let preference: string | null = user.foodPreference;
      if (preference !== null) {
        preference = preference.toLowerCase();
      }
      params = {...params, foodPreference: preference};
    }

    return await axios.patch(`${Config.apiBaseUrl}/user/self/update`, params, {
      headers: WithAuthHeader(authToken),
    });
  }

  async exchangeToken(token: string): Promise<string> {
    return await axios
      .get(`${Config.apiBaseUrl}/login/token`, {
        params: {code: token},
      })
      .then(value => this.responseMapper.map(value))
      .then(value =>
        this.schemaMapper.map<AuthorizationToken>(
          AuthorizationTokenSchema,
          value
        )
      )
      .then(value => value.token);
  }

  async getUrl(): Promise<string> {
    return await axios
      .get(`${Config.apiBaseUrl}/login/url`)
      .then(value => this.responseMapper.map(value))
      .then(value =>
        this.schemaMapper.map<AuthorizationUri>(AuthorizationUriSchema, value)
      )
      .then(value => value.authorizationUri);
  }

  async logout(): Promise<void> {
    // TODO
    return;
  }
}
