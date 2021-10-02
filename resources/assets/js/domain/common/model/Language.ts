import Config from "../../../Config";

type languages = Lowercase<keyof typeof Config.SUPPORTED_LANGUAGES>;

export default class Language {
    constructor(readonly language: languages) {
    }
}

type AllLanguages = {
    [Properties in languages]: Language
}


export const SupportedLanguages = {
    nl: new Language('nl'),
    en: new Language('en')
}

let typecheck: AllLanguages = SupportedLanguages;