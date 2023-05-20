import {GetLanguage} from './domain/storage/usecase/GetLanguage';
import {Language} from './domain/common/model/Language';
import {AnonymousUser, AuthenticatedState} from './domain/common/model/User';
import {injectable} from 'tsyringe';

export interface Local {
  language: Language;
  user: AuthenticatedState;
}

@injectable()
export class GetDefaultData {
  constructor(private getLanguage: GetLanguage) {}

  get(): Local {
    return {
      language: this.getLanguage.execute(),
      user: AnonymousUser,
    };
  }
}
