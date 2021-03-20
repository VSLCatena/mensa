import Mensa from '../../../domain/mensa/model/Mensa';
import MensaSignup from '../../../domain/mensa/model/MensaSignup';
import { MensaRepository } from '../../../domain/mensa/repository/MensaRepository';
import api from '../../api/Api';

class MensaRepositoryImpl implements MensaRepository {

    async getMensas(limit: number, fromLastId?: string): Promise<Mensa[]> {
        let response = await api.GET("mensas");
        return [];
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