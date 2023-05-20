import {SupportedLanguages} from '../../domain/common/model/Language';

export interface LanguageBlock {
  [key: string]: LanguageText | LanguageBlock;
}

export type LanguageText = {
  [Property in keyof typeof SupportedLanguages]: string;
};
