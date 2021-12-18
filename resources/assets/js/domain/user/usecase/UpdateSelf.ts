import repository from "../repository/UserRepository";
import {UpdatableUser} from "../../common/model/User";
import WithAuthentication from "../../common/usecase/WithAuthentication";


export default async function UpdateSelf(user: UpdatableUser): Promise<void> {
    return WithAuthentication((token: string | null) => repository.updateSelf(token as string, user))
        .then(() => {
        });
}