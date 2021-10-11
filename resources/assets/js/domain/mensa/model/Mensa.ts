import ExtraOption from "./ExtraOption";
import {SimpleUser} from "../../common/model/User";
import MensaMenuItem from "./MensaMenuItem";
import FoodOption from "./FoodOption";

export default interface Mensa {
    id: string,
    title: string,
    description: string,
    foodOptions: FoodOption[],
    menu: MensaMenuItem[],
    extraOptions: ExtraOption[],
    date: Date,
    closingTime: Date,
    price: number,
    maxSignups: number,
    signups: number|SimpleUser[],
    cooks: SimpleUser[],
    dishwashers: number,
}