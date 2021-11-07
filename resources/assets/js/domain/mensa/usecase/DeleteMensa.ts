import repository from "../repository/MensaRepository";
import MensaSignup from "../../signup/model/MensaSignup";
import Mensa from "../model/Mensa";
import WithAuthentication, {Strategy} from "../../common/usecase/WithAuthentication";

export default async function DeleteMensa(mensa: Mensa): Promise<void> {
    return WithAuthentication(
        token => repository.deleteMensa(mensa, token!!),
        Strategy.AUTH_REQUIRED
    );
}