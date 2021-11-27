import AppConfig from "../model/AppConfig";
import repository from "../../../data/appconfig/repository/AppConfigDataRepository";

export default async function GetAppConfig(): Promise<AppConfig> {
    return repository.getAppConfig()
}