import repository from "../repository/UserRepository";
import SetUserToken from "../../storage/usecase/SetUserToken";

export default async function Login(token: string): Promise<void> {
    let code = await repository.exchangeToken(token);
    return SetUserToken(code);
}