import Vue from 'vue';
import Date from './Date';
import Text from './Text';
import Validation from './Validation'
import {LanguageLintCheck, LanguageText} from "./LanguageText";
import Language from "../../domain/common/model/Language";

export function translatedText(language: Language, text: LanguageText): string {
    return text[language.language];
}

const language = {
    date: Date,
    text: Text,
    validation: Validation,
}

export function translate(
    text: LanguageText,
    capitalize: boolean = false,
    language: Language = Vue.prototype.$local.language
): string {
    let txt = translatedText(language, text);
    if (capitalize) txt = txt.charAt(0).toUpperCase() + txt.slice(1);
    return txt;
}

// Make sure it is in the correct format
LanguageLintCheck(language)

export default language;