import repository from "../../../data/user/repository/UserRepositoryImpl";
import {FullUser} from "../../common/model/User";

export interface UserRepository {
    getUrl(): Promise<string>
    exchangeToken(token: string): Promise<string>
    logout(): Promise<void>
    getSelf(authToken: string): Promise<FullUser>
}

export default repository;