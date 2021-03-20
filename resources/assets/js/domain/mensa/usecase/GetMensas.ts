import Mensa from "../model/Mensa";
import repository from "../repository/MensaRepository";

export default async function GetMensas(limit: number, fromLastId?: string): Promise<Mensa[]> {
    return repository.getMensas(limit, fromLastId)
}