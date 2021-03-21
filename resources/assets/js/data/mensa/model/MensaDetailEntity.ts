import MensaSignupEntity from "./MensaSignupEntity";
import PriceEntity from "./PriceEntity";

export default interface MensaDetailEntity {
    id: string,
    title: string,
    description: string,
    prices: PriceEntity[],
    date: string,
    closeTime: string,
    maxSignups: number,
    users: MensaSignupEntity[],
}