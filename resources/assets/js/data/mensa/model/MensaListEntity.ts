import MensaEntity from "./MensaEntity";

export default interface MensaListEntity {
    between?: BetweenEntity,
    mensas?: MensaEntity[]
}

export interface BetweenEntity {
    start?: number,
    end?: number
}