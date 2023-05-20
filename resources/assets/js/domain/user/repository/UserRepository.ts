import {FullUser, UserPreferences} from '../../common/model/User';

export interface UserRepository {
  getUrl: () => Promise<string>;

  exchangeToken: (token: string) => Promise<string>;

  logout: () => Promise<void>;

  getSelf: (authToken: string) => Promise<FullUser>;

  updateSelf: (authToken: string, user: UserPreferences) => Promise<void>;
}
