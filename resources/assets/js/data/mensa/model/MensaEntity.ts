import ExtraOption from "../../../domain/mensa/model/ExtraOption";
import MensaSimpleUserEntity from "./MensaSimpleUserEntity";

export default interface MensaEntity {
    id?: string,
    title?: string,
    description?: string,
    extraOptions?: ExtraOption[],
    date?: string,
    closingTime?: string,
    maxSignups?: number,
    signups?: number,
    cooks?: MensaSimpleUserEntity[],
    dishwashers?: number,
}