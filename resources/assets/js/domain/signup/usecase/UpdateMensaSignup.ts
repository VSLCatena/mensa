import repository from "../../signup/repository/SignupRepository";
import MensaSignup from "../model/MensaSignup";
import Mensa from "../../mensa/model/Mensa";
import WithAuthentication, {Strategy} from "../../common/usecase/WithAuthentication";

export default async function UpdateMensaSignup(
    mensa: Mensa,
    email: string,
    signups: MensaSignup[]
): Promise<void> {
    return WithAuthentication(
        token => repository.editSignup(mensa, email, signups, token),
        Strategy.AUTH_OPTIONAL
    );
}