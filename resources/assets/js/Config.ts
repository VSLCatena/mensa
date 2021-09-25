const APP_NAME = process.env.APP_NAME;
const URL_PATH = process.env.API_URL;
const VERSION = process.env.API_VERSION;
const API_BASE_URL = `${URL_PATH}/api/${VERSION}`;
const DEBUG = process.env.APP_DEBUG;

enum SupportedLanguages {
    NL, EN
}

export default {
    APP_NAME: APP_NAME,
    API_BASE_URL: API_BASE_URL,
    DEBUG: DEBUG,
    SUPPORTED_LANGUAGES: SupportedLanguages
}