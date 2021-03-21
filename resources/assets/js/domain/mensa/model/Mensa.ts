import ExtraOption from "./ExtraOption";
import SimpleUser from "./SimpleUser";

export default interface Mensa {
    id: string,
    title: string,
    description: string,
    extraOptions: ExtraOption[],
    date: string,
    closingTime: string,
    maxSignups: number,
    signups: number,
    cooks: SimpleUser[],
    dishwashers: number,
}