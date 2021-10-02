import repository from "../repository/MensaRepository";
import MensaList from "../model/MensaList";

export default async function GetMensas(weekOffset: number): Promise<MensaList> {
    return repository.getMensas(weekOffset)
}