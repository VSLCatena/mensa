import repository from "../repository/StorageRepository";
import Language from "../../common/model/Language";

export default function GetLanguage(): Language {
    return repository.getLanguage();
}