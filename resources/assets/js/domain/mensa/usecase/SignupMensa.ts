import repository from "../repository/MensaRepository";
import MensaSignup from "../model/MensaSignup";
import Mensa from "../model/Mensa";

export default async function SignupMensa(
    mensa: Mensa,
    email: string,
    signups: Partial<MensaSignup>[]
): Promise<void> {
    return repository.signup(mensa, email, signups);
}