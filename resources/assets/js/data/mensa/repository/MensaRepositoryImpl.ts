import Mensa from '../../../domain/mensa/model/Mensa';
import MensaSignup from '../../../domain/mensa/model/MensaSignup';
import { MensaRepository } from '../../../domain/mensa/repository/MensaRepository';
import {ServiceBuilder} from "ts-retrofit";
import { API_BASE_URL } from '../../../config';
import MapMensas from "../mapper/MapMensas";
import axios from "axios";

class MensaRepositoryImpl implements MensaRepository {

    async getMensas(limit: number, fromLastId?: string): Promise<Mensa[]> {
        try {
            let result = await axios.get(`${API_BASE_URL}/mensa/list`, {
                params: {
                    limit: limit,
                    fromLastId: fromLastId
                }
            });

            return MapMensas(result.data).getOrThrow();
        } catch (e) {
            return Promise.reject(e);
        }
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
    signup(signup: MensaSignup): Promise<Error | null> {
        throw new Error('Method not implemented.');
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