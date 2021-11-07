import Mensa from "../model/Mensa";
import repository from '../../../data/mensa/repository/MensaRepositoryImpl';
import MensaList from "../model/MensaList";

export interface MensaRepository {
    getMensas(weekOffset: number|null, authToken: string|null): Promise<MensaList>

    getMensa(mensaId: string): Promise<Mensa>
    addMensa(mensa: Mensa, authToken: string): Promise<void>
    editMensa(mensa: Mensa, authToken: string): Promise<void>
    deleteMensa(mensa: Mensa, authToken: string): Promise<void>
}

export default repository;