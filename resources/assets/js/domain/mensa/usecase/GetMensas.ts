import repository from "../repository/MensaRepository";
import MensaList from "../model/MensaList";
import WithAuthentication, {Strategy} from "../../common/usecase/WithAuthentication";

export default async function GetMensas(weekOffset: number): Promise<MensaList> {
    return WithAuthentication(token => repository.getMensas(weekOffset, token), Strategy.AUTH_OPTIONAL)
}