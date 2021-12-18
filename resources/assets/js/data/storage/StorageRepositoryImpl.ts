import {StorageRepository} from "../../domain/storage/repository/StorageRepository";
import Cookies from "js-cookie";
import Language, {SupportedLanguages} from "../../domain/common/model/Language";

const DARK_MODE_KEY = "DARK_MODE_KEY";
const LANGUAGE_KEY = "LANGUAGE_KEY";
const TOKEN_KEY = "TOKEN_KEY";

class StorageRepositoryImpl implements StorageRepository {
    getDarkMode(): boolean {
        return Cookies.get(DARK_MODE_KEY) != "false"
    }

    setDarkMode(mode: boolean): void {
        Cookies.set(DARK_MODE_KEY, mode ? 'true' : 'false');
    }

    getLanguage(): Language {
        let language = Cookies.get(LANGUAGE_KEY);
        if (language == 'en') {
            return SupportedLanguages.en;
        } else {
            return SupportedLanguages.nl;
        }
    }

    setLanguage(language: Language): void {
        Cookies.set(LANGUAGE_KEY, language.language);
    }

    getUserToken(): string | undefined {
        return Cookies.get(TOKEN_KEY);
    }

    setUserToken(token: string | null) {
        if (token == null) {
            Cookies.remove(TOKEN_KEY);
        } else {
            Cookies.set(TOKEN_KEY, token);
        }
    }
}

const repository = new StorageRepositoryImpl();
export default repository;