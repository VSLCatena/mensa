const APP_NAME = process.env.APP_NAME ?? '';
const URL_PATH = process.env.API_URL ?? '';
const VERSION = process.env.API_VERSION ?? '';
const API_BASE_URL = `${URL_PATH}/api/${VERSION}`;
const DEBUG = process.env.APP_DEBUG;
const CHANGE_PASSWORD_LINK = process.env.CHANGE_PASSWORD_LINK;

enum SupportedLanguages {
  NL,
  EN,
}

interface Config {
  appName: string;
  apiBaseUrl: string;
  debug: boolean;
  supportedLanguages: typeof SupportedLanguages;
  changePasswordLink: string;
}

export const Config: Config = {
  appName: String(APP_NAME),
  apiBaseUrl: String(API_BASE_URL),
  debug: Boolean(DEBUG),
  supportedLanguages: SupportedLanguages,
  changePasswordLink: String(CHANGE_PASSWORD_LINK),
};
