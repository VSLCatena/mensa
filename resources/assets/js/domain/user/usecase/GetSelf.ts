import repository from "../repository/UserRepository";
import {AnonymousUser, AuthUser} from "../../common/model/User";
import WithAuthentication from "../../common/usecase/WithAuthentication";

export default async function GetSelf(): Promise<AuthUser> {
    return WithAuthentication(token => repository.getSelf(token as string))
        .catch(() => Promise.resolve(AnonymousUser));
}