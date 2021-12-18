import {PartialExtraOption, PartialMensaMenuItem} from "../../../domain/mensa/model/EditMensa";

export default interface MensaRequestModel {
    id?: string,
    title: string,
    description: string,
    foodOptions: string[],
    menu: PartialMensaMenuItem[],
    extraOptions: PartialExtraOption[],
    date: number,
    closingTime: number,
    price: number,
    maxSignups: number
}