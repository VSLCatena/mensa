import AppConfig from "../../../domain/appconfig/model/AppConfig";
import axios from "axios";
import Config from "../../../Config";
import MapResponse from "../../utils/MapResponse";
import MapAppConfig from "../mapper/MapAppConfig";

class AppConfigCache {
    private cachedAppConfig: AppConfig|null = null

    async getAppConfig(): Promise<AppConfig> {
        if (this.cachedAppConfig != null) return this.cachedAppConfig;

        try {
            let response = await this.doAppConfigCall();
            this.cachedAppConfig = response;
            return Promise.resolve(response);
        } catch (e) {
            return Promise.reject(e)
        }
    }

    private doAppConfigCall(): Promise<AppConfig> {
        return axios.get(`${Config.API_BASE_URL}/appconfig`)
            .then(MapResponse)
            .then(value => MapAppConfig(value).asPromise());
    }
}

const cache = new AppConfigCache();
export default cache;