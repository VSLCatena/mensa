import Mensa from "../model/Mensa";
import MensaSignup from "../model/MensaSignup";
import repository from '../../../data/mensa/repository/MensaRepositoryImpl';
import MensaList from "../model/MensaList";

export interface MensaRepository {
    getMensas(weekOffset: number|null, authToken: string|null): Promise<MensaList>

    getMensa(mensaId: string): Promise<Mensa|null>
    addMensa(mensa: Mensa): Promise<Error|null>
    editMensa(mensa: Mensa): Promise<Error|null>
    deleteMensa(mensaId: string): Promise<Error|null>

    getSignup(mensaId: string): Promise<MensaSignup|null>
    signup(mensa: Mensa, email: string, signups: MensaSignup[], authToken: string|null): Promise<void>
    editSignup(signup: MensaSignup): Promise<Error|null>
    signout(mensaId: string): Promise<Error|null>
}

export default repository;