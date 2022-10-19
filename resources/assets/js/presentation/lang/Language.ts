import Vue from 'vue';
import {Date} from './Date';
import {Text} from './Text';
import {Validation} from './Validation';
import {LanguageLintCheck, LanguageText} from './LanguageText';
import {Language as Lang} from '../../domain/common/model/Language';

export function translatedText(language: Lang, text: LanguageText): string {
  return text[language.language];
}

export const Language = {
  date: Date,
  text: Text,
  validation: Validation,
};

export function translate(
  text: LanguageText,
  capitalize = false,
  language: Lang = Vue.prototype.$local.language
): string {
  let txt = translatedText(language, text);
  if (capitalize) txt = txt.charAt(0).toUpperCase() + txt.slice(1);
  return txt;
}

// Make sure it is in the correct format
LanguageLintCheck(Language);
