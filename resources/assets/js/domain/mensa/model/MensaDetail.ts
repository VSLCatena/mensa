import ExtraOption from "./ExtraOption";
import MensaSignup from "./MensaSignup";

export default interface MensaDetail {
    id: string,
    title: string,
    description: string,
    prices: ExtraOption[],
    date: string,
    closeTime: string,
    maxSignups: number,
    users: MensaSignup[],
}