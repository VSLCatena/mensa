import Date from './Date';
import Text from './Text';
import Validation from './Validation'
import Vue from "vue";
import {LanguageLintCheck, LanguageText} from "./LanguageText";
import Language from "../../domain/common/model/Language";
import GetLanguage from "../../domain/storage/usecase/GetLanguage";

export const CurrentLanguage = Vue.observable({ language: GetLanguage() });

export function translatedText(language: Language, text: LanguageText): string {
    return text[language.language];
}

const language = {
    date: Date,
    text: Text,
    validation: Validation,
}

// Make sure it is in the correct format
LanguageLintCheck(language)

export default language;