import {Languages} from "./Language";

export interface ILanguage {
    readonly [key: string]: string;
}

export interface LanguageBlock {
    [key: string]: LanguageText|LanguageBlock
}

export type LanguageText = {
    [Property in keyof typeof Languages]: string;
}