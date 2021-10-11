import repository from "../repository/MensaRepository";
import MensaSignup from "../model/MensaSignup";
import Mensa from "../model/Mensa";
import WithAuthentication, {Strategy} from "../../common/usecase/WithAuthentication";

export default async function SignupMensa(
    mensa: Mensa,
    email: string,
    signups: Partial<MensaSignup>[]
): Promise<void> {
    return WithAuthentication(token =>
        repository.signup(mensa, email, signups, token), Strategy.AUTH_OPTIONAL
    );
}