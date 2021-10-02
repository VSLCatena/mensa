import { UserRepository } from "../../../domain/user/repository/UserRepository";
import axios from "axios";
import Config from "../../../Config";
import MapAuthorizationUri from "../mapper/MapAuthorizationUri";
import MapResponse from "../../utils/MapResponse";
import MapToken from "../mapper/MapToken";
import {FullUser} from "../../../domain/common/model/User";
import {MapFullUser, MapUser} from "../../mensa/mapper/MapUsers";
import WithAuthHeader from "../../utils/WithAuthHeader";

class UserRepositoryImpl implements UserRepository {
    getSelf(authToken: string): Promise<FullUser> {
        return axios.get(`${Config.API_BASE_URL}/user/self`, {
            headers: WithAuthHeader(authToken)
        })
            .then(MapResponse)
            .then(value => MapFullUser(value).asPromise())
    }

    exchangeToken(token: string): Promise<string> {
        return axios.get(`${Config.API_BASE_URL}/login/token`, {
            params: { code: token }
        })
            .then(MapResponse)
            .then(value => MapToken(value).asPromise())
    }

    getUrl(): Promise<string> {
        return axios.get(`${Config.API_BASE_URL}/login/url`)
            .then(MapResponse)
            .then(value => MapAuthorizationUri(value).asPromise())
    }

    logout(): Promise<void> {
        return Promise.resolve(undefined);
    }

}

const repository = new UserRepositoryImpl();
export default repository;