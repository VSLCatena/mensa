import ExtraOption from "./ExtraOption";
import {SimpleUser} from "../../common/model/User";
import MensaMenuItem from "./MensaMenuItem";

export default interface Mensa {
    id: string,
    title: string,
    description: string,
    menu: MensaMenuItem[],
    extraOptions: ExtraOption[],
    date: Date,
    closingTime: Date,
    price: number,
    maxSignups: number,
    signups: number,
    cooks: SimpleUser[],
    dishwashers: number,
}