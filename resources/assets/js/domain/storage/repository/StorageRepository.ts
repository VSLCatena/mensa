import {Language} from '../../common/model/Language';

export interface StorageRepository {
  getDarkMode: () => boolean;

  setDarkMode: (mode: boolean) => void;

  getLanguage: () => Language;

  setLanguage: (language: Language) => void;

  getUserToken: () => string | undefined;

  setUserToken: (token: string | null) => void;
}
