import {AppConfigRepository} from "../../../domain/appconfig/repository/AppConfigRepository";
import AppConfig from "../../../domain/appconfig/model/AppConfig";
import cache from "../cache/AppConfigCache";

class AppConfigDataRepository implements AppConfigRepository {
    getAppConfig(): Promise<AppConfig> {
        return cache.getAppConfig();
    }

}

const repository = new AppConfigDataRepository();
export default repository;