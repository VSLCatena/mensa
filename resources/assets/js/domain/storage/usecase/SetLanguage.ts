import repository from "../repository/StorageRepository";
import Language from "../../common/model/Language";

export default function SetLanguage(language: Language) {
    return repository.setLanguage(language);
}