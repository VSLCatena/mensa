import Mensa from "./Mensa";

export default interface MensaList {
    between: Between,
    mensas: Mensa[]
}

export interface Between {
    start: Date,
    end: Date
}