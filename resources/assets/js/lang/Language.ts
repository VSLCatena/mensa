import Date from './Date';
import Text from './Text';
import Validation from './Validation'
import Vue from "vue";
import { LanguageLintCheck, LanguageText} from "./LanguageTypes";

export class Language {
    constructor(
        readonly language: keyof typeof Languages
    ) {
    }

    getText(text: LanguageText): string {
        return text[this.language];
    }
}

export const Languages = {
    nl: "nl",
    en: "en",
}

export const CurrentLanguage = Vue.observable({ language: new Language("nl") });


const language = {
    date: Date,
    text: Text,
    validation: Validation,
}

// Make sure it is in the correct format
LanguageLintCheck(language)

export default language;