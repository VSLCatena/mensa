import Mensa from "../model/Mensa";
import MensaSignup from "../model/MensaSignup";
import repository from '../../../data/mensa/repository/MensaRepositoryImpl';

export interface MensaRepository {
    getMensas(limit: number, fromId?: string): Promise<Mensa[]>

    getMensa(mensaId: string): Promise<Mensa|null>
    addMensa(mensa: Mensa): Promise<Error|null>
    editMensa(mensa: Mensa): Promise<Error|null>
    deleteMensa(mensaId: string): Promise<Error|null>

    getSignup(mensaId: string): Promise<MensaSignup|null>
    signup(signup: MensaSignup): Promise<Error|null>
    editSignup(signup: MensaSignup): Promise<Error|null>
    signout(mensaId: string): Promise<Error|null>
}

export default repository;