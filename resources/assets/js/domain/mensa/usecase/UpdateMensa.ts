import repository from "../repository/MensaRepository";
import Mensa from "../model/Mensa";
import WithAuthentication, {Strategy} from "../../common/usecase/WithAuthentication";

export default async function UpdateMensa(mensa: Mensa): Promise<void> {
    return WithAuthentication(
        token => repository.editMensa(mensa, token!!),
        Strategy.AUTH_REQUIRED
    );
}