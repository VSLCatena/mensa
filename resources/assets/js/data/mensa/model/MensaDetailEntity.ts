import MensaSignupEntity from "./MensaSignupEntity";
import MensaExtraOptionsEntity from "./MensaExtraOptionsEntity";

export default interface MensaDetailEntity {
    id: string,
    title: string,
    description: string,
    prices: MensaExtraOptionsEntity[],
    date: string,
    closeTime: string,
    maxSignups: number,
    users: MensaSignupEntity[],
}