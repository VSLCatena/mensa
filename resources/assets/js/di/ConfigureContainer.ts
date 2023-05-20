import {Lifecycle} from "tsyringe";
import DependencyContainer from "tsyringe/dist/typings/types/dependency-container";
import {TypeSymbols} from "./TypeSymbols";
import {AppConfigDataRepository} from "../data/appconfig/repository/AppConfigDataRepository";
import {MensaDataRepository} from "../data/mensa/repository/MensaDataRepository";
import {SignupDataRepository} from "../data/signup/repository/SignupDataRepository";
import {StorageDataRepository} from "../data/storage/repository/StorageDataRepository";
import {UserDataRepository} from "../data/user/repository/UserDataRepository";
import {FaqDataRepository} from "../data/faq/repository/FaqDataRepository";

export function configureContainer(container: DependencyContainer): DependencyContainer {
  return container
    .register(TypeSymbols.AppConfigRepository, {
      useClass: AppConfigDataRepository,
    }, { lifecycle: Lifecycle.ResolutionScoped })
    .register(TypeSymbols.FaqRepository, {
      useClass: FaqDataRepository,
    }, { lifecycle: Lifecycle.ResolutionScoped })
    .register(TypeSymbols.MensaRepository, {
      useClass: MensaDataRepository,
    }, { lifecycle: Lifecycle.ResolutionScoped })
    .register(TypeSymbols.SignupRepository, {
      useClass: SignupDataRepository,
    }, { lifecycle: Lifecycle.ResolutionScoped })
    .register(TypeSymbols.StorageRepository, {
      useClass: StorageDataRepository
    }, { lifecycle: Lifecycle.ResolutionScoped })
    .register(TypeSymbols.UserRepository, {
      useClass: UserDataRepository
    }, { lifecycle: Lifecycle.ResolutionScoped });
}
