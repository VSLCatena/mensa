import Mensa from "../model/Mensa";
import repository from "../repository/MensaRepository";

export default async function GetMensa(mensaId: string): Promise<Mensa | null> {
    return repository.getMensa(mensaId)
}