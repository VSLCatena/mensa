import ExtraOption from "../../../domain/mensa/model/ExtraOption";
import MensaSimpleUserEntity from "./MensaSimpleUserEntity";

export default interface MensaEntity {
    id?: string,
    title?: string,
    description?: string,
    extraOptions?: ExtraOption[],
    date?: number,
    closingTime?: number,
    maxSignups?: number,
    signups?: number,
    price?: number,
    cooks?: MensaSimpleUserEntity[],
    dishwashers?: number,
}