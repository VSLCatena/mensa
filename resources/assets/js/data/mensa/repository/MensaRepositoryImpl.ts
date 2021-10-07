import Mensa from '../../../domain/mensa/model/Mensa';
import MensaSignup from '../../../domain/mensa/model/MensaSignup';
import { MensaRepository } from '../../../domain/mensa/repository/MensaRepository';
import Config from '../../../Config';
import MapMensas from "../mapper/MapMensas";
import axios from "axios";
import MensaList from "../../../domain/mensa/model/MensaList";
import MapResponse from "../../utils/MapResponse";

class MensaRepositoryImpl implements MensaRepository {

    async getMensas(weekOffset: number|null): Promise<MensaList> {
        return axios.get(`${Config.API_BASE_URL}/mensas`, {
            params: { weekOffset: weekOffset }
        })
            .then(MapResponse)
            .then(value => MapMensas(value).asPromise());
    }
    getMensa(mensaId: string): Promise<Mensa | null> {
        throw new Error('Method not implemented.');
    }
    addMensa(mensa: Mensa): Promise<Error | null> {
        throw new Error('Method not implemented.');
    }
    editMensa(mensa: Mensa): Promise<Error | null> {
        throw new Error('Method not implemented.');
    }
    deleteMensa(mensaId: string): Promise<Error | null> {
        throw new Error('Method not implemented.');
    }
    getSignup(mensaId: string): Promise<MensaSignup | null> {
        throw new Error('Method not implemented.');
    }
    signup(mensa: Mensa, email: string, signups: Partial<MensaSignup>[]): Promise<void> {
        return axios.post(`${Config.API_BASE_URL}/mensa/${mensa.id}/signup`, {
            email: email,
            signups: signups
        }).then(() => {})
    }
    editSignup(signup: MensaSignup): Promise<Error | null> {
        throw new Error('Method not implemented.');
    }
    signout(mensaId: string): Promise<Error | null> {
        throw new Error('Method not implemented.');
    }   
}

const repository = new MensaRepositoryImpl();
export default repository;