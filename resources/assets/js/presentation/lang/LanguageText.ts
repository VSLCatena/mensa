import {SupportedLanguages} from '../../domain/common/model/Language';

export interface ILanguage {
  readonly [key: string]: string;
}

export interface LanguageBlock {
  [key: string]: LanguageText | LanguageBlock;
}

export type LanguageText = {
  [Property in keyof typeof SupportedLanguages]: string;
};

// This is only used for lint checks
// noinspection JSUnusedLocalSymbols
// eslint-disable-next-line @typescript-eslint/no-unused-vars
export function LanguageLintCheck(_block: LanguageBlock): void {
  // NO-OP
}
