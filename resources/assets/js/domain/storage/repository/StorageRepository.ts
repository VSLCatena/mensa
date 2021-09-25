import Language from "../../common/model/Language";
import repository from "../../../data/storage/StorageRepositoryImpl";

export interface StorageRepository {
    getDarkMode(): Boolean
    setDarkMode(mode: Boolean): void

    getLanguage(): Language
    setLanguage(language: Language): void
}

export default repository;