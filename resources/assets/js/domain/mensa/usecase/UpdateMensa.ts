import repository from "../repository/MensaRepository";
import WithAuthentication, {Strategy} from "../../common/usecase/WithAuthentication";
import EditMensa from "../model/EditMensa";
import MapMensaRequest from "../mapper/MapMensaRequest";

export default async function UpdateMensa(mensa: EditMensa): Promise<void> {
    return WithAuthentication(
        token => repository.editMensa(MapMensaRequest(mensa), token!!),
        Strategy.AUTH_REQUIRED
    );
}