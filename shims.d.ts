import {LanguageText} from './resources/assets/js/presentation/lang/LanguageText';
import {Language} from './resources/assets/js/presentation/lang/Language';
import {Local} from './resources/assets/js/Local';
import {SupportedLanguages} from './resources/assets/js/domain/common/model/Language';


declare module '*.vue' {
  // @ts-ignore
  import type {DefineComponent} from 'vue';
  const component: DefineComponent<{}, {}, any>;
  export default component;
}

declare module '@vue/runtime-core' {
  interface ComponentCustomProperties {
    $local: Local;
    $lang: typeof Language;
    $ll: (
      text: LanguageText,
      capitalize: boolean,
      language: keyof typeof Language
    ) => string;
  }
}

declare module 'vue/types/vue' {
  interface Vue {
    $local: Local;
    $lang: typeof Language;
    $ll: (
      text: LanguageText,
      capitalize?: boolean,
      language?: keyof typeof SupportedLanguages
    ) => string;
  }
}
