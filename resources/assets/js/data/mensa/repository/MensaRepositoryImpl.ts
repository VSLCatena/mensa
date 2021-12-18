import Mensa from '../../../domain/mensa/model/Mensa';
import {MensaRepository} from '../../../domain/mensa/repository/MensaRepository';
import Config from '../../../Config';
import MapMensas from "../mapper/MapMensas";
import axios from "axios";
import MensaList from "../../../domain/mensa/model/MensaList";
import MapResponse from "../../utils/MapResponse";
import WithAuthHeader, {OptionalAuthHeader} from "../../utils/WithAuthHeader";
import MensaRequestModel from "../model/MensaRequestModel";

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

    addMensa(mensa: MensaRequestModel, authToken: string): Promise<void> {
        return axios.post(`${Config.API_BASE_URL}/mensa/new`, mensa, {
            headers: WithAuthHeader(authToken)
        })
            .then(MapResponse)
            .then();
    }

    editMensa(mensa: MensaRequestModel, authToken: string): Promise<void> {
        if (!('id' in mensa)) {
            throw Error("ID not found in mensa");
        }

        return axios.patch(`${Config.API_BASE_URL}/mensa/${mensa.id}`, mensa, {
            headers: WithAuthHeader(authToken)
        })
            .then(MapResponse)
            .then();
    }

    deleteMensa(mensaId: String, authToken: string): Promise<void> {
        return axios.delete(`${Config.API_BASE_URL}/mensa/${mensaId}`, {
            headers: WithAuthHeader(authToken),
        }).then(MapResponse);
    }
}

const repository = new MensaRepositoryImpl();
export default repository;