// noinspection JSUnusedLocalSymbols

import {Config} from '../../../Config';

type languages = Lowercase<keyof typeof Config.supportedLanguages>;

export class Language {
  constructor(readonly language: languages) {}
}

type AllLanguages = {
  [Properties in languages]: Language;
};

export const SupportedLanguages = {
  nl: new Language('nl'),
  en: new Language('en'),
};

// eslint-disable-next-line @typescript-eslint/no-unused-vars
const typeCheck: AllLanguages = SupportedLanguages;
