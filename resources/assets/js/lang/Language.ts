import Date from './Date';
import Vue from "vue";
import {LanguageBlock, LanguageText} from "./LanguageTypes";

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


const language: LanguageBlock = {
    date: Date
}

export default language;