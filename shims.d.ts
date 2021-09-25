import {LanguageText} from "./resources/assets/js/presentation/lang/LanguageText";
import Translations, {Language} from "./resources/assets/js/presentation/lang/Language";
import {AuthUser} from "./resources/assets/js/domain/common/model/User";

declare module '*.vue' {
  import type { DefineComponent } from 'vue'
  const component: DefineComponent<{}, {}, any>
  export default component
}

declare module '@vue/runtime-core' {

  interface ComponentCustomProperties {
    $user: AuthUser,
    $lang: Translations,
    $currentLanguage: { language: Language },
    $ll: (text: LanguageText, capitalize: boolean, language: Language) => string,
  }
}

declare module 'vue/types/vue' {
  interface Vue {
    $user: AuthUser,
    $lang: Translations,
    $currentLanguage: { language: Language },
    $ll: (text: LanguageText, capitalize?: boolean, language?: Language) => string,
  }
}
