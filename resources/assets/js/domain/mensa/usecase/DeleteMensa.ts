import repository from "../repository/MensaRepository";
import WithAuthentication, {Strategy} from "../../common/usecase/WithAuthentication";

export default async function DeleteMensa(mensaId: string): Promise<void> {
    return WithAuthentication(
        token => repository.deleteMensa(mensaId, token!!),
        Strategy.AUTH_REQUIRED
    );
}