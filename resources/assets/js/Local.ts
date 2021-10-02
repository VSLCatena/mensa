import GetLanguage from "./domain/storage/usecase/GetLanguage";
import Language from "./domain/common/model/Language";
import {AnonymousUser, AuthUser} from "./domain/common/model/User";

export interface Local {
    language: Language,
    user: AuthUser,
}

export function defaultData(): Local {
    return {
        language: GetLanguage(),
        user: AnonymousUser
    }
}
