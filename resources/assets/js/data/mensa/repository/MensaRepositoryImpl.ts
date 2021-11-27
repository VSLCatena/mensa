import Mensa from '../../../domain/mensa/model/Mensa';
import MensaSignup from '../../../domain/signup/model/MensaSignup';
import {MensaRepository} from '../../../domain/mensa/repository/MensaRepository';
import Config from '../../../Config';
import MapMensas from "../mapper/MapMensas";
import axios from "axios";
import MensaList from "../../../domain/mensa/model/MensaList";
import MapResponse from "../../utils/MapResponse";
import WithAuthHeader, {OptionalAuthHeader} from "../../utils/WithAuthHeader";
import EditMensa from "../../../domain/mensa/model/EditMensa";

class MensaRepositoryImpl implements MensaRepository {

    async getMensas(
        weekOffset: number | null,
        authToken: string | null
    ): Promise<MensaList> {
        return axios.get(`${Config.API_BASE_URL}/mensas`, {
            headers: OptionalAuthHeader(authToken),
            params: {weekOffset: weekOffset}
        })
            .then(MapResponse)
            .then(value => MapMensas(value).asPromise());
    }

    getMensa(mensaId: string): Promise<Mensa> {
        throw new Error('Method not implemented.');
    }

    addMensa(mensa: EditMensa, authToken: string): Promise<void> {
        throw new Error('Method not implemented.');
    }

    editMensa(mensa: EditMensa, authToken: string): Promise<void> {
        throw new Error('Method not implemented.');
    }

    deleteMensa(mensaId: String, authToken: string): Promise<void> {
        return axios.delete(`${Config.API_BASE_URL}/mensa/${mensaId}`, {
            headers: WithAuthHeader(authToken),
        }).then(MapResponse);
    }
}

const repository = new MensaRepositoryImpl();
export default repository;