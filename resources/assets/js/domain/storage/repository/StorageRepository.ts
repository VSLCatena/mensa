import Language from "../../common/model/Language";
import repository from "../../../data/storage/StorageRepositoryImpl";

export interface StorageRepository {
    getDarkMode(): Boolean

    setDarkMode(mode: Boolean): void

    getLanguage(): Language

    setLanguage(language: Language): void

    getUserToken(): string | undefined

    setUserToken(token: string | null): void
}

export default repository;