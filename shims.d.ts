import {LanguageText} from "./resources/assets/js/presentation/lang/LanguageText";
import Translations, {Language} from "./resources/assets/js/presentation/lang/Language";
import {Local} from "./resources/assets/js/Local";

declare module '*.vue' {
  import type { DefineComponent } from 'vue'
  const component: DefineComponent<{}, {}, any>
  export default component
}

declare module '@vue/runtime-core' {

  interface ComponentCustomProperties {
    $local: Local,
    $lang: Translations,
    $ll: (text: LanguageText, capitalize: boolean, language: Language) => string,
  }
}

declare module 'vue/types/vue' {
  interface Vue {
    $local: Local,
    $lang: Translations,
    $ll: (text: LanguageText, capitalize?: boolean, language?: Language) => string,
  }
}
