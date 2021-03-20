import Price from "./Price";
import MensaSignup from "./MensaSignup";

export default interface Mensa {
    id: string,
    title: string,
    description: string,
    prices: Price[],
    date: number,
    closeTime: number,
    maxSignups: number,
    cooks: MensaSignup[],
    dishwashers: MensaSignup[]|number,
    users: MensaSignup[]|number,
}