import {DIModule} from './DIModule';
import {AppConfigModule} from '../appconfig/module/AppConfigModule';
import {FaqModule} from '../faq/module/FaqModule';
import {MensaModule} from '../mensa/module/MensaModule';
import {SignupModule} from '../signup/module/SignupModule';
import {StorageModule} from '../storage/module/StorageModule';
import {UserModule} from '../user/module/UserModule';

export const DIModules: (new () => DIModule)[] = [
  AppConfigModule,
  FaqModule,
  MensaModule,
  SignupModule,
  StorageModule,
  UserModule,
];
