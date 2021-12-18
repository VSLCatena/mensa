import Mensa from "../model/Mensa";
import repository from '../../../data/mensa/repository/MensaRepositoryImpl';
import MensaList from "../model/MensaList";
import MensaRequestModel from "../../../data/mensa/model/MensaRequestModel";

export interface MensaRepository {
    getMensas(weekOffset: number | null, authToken: string | null): Promise<MensaList>

    getMensa(mensaId: string): Promise<Mensa>

    addMensa(mensa: MensaRequestModel, authToken: string): Promise<void>

    editMensa(mensa: MensaRequestModel, authToken: string): Promise<void>

    deleteMensa(mensaId: string, authToken: string): Promise<void>
}

export default repository;