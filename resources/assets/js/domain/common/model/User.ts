import {FoodOption} from '../../mensa/model/FoodOption';

export interface AuthUser {
  isAdmin: boolean;
}

export interface IdentifiableUser {
  id: string;
  name: string;
}

export interface UserPreferences {
  foodPreference: FoodOption | null;
  extraInfo: string;
  allergies: string;
}

export interface UpdatableUser extends IdentifiableUser, UserPreferences {}
export interface FullUser extends AuthUser, UpdatableUser {
  email: string;
}

export type AuthenticatedState = AuthUser | FullUser;

export function isLoggedIn(state: AuthenticatedState): state is FullUser {
  return (state as FullUser).name !== undefined;
}

export const AnonymousUser: AuthUser = {
  isAdmin: false,
};
