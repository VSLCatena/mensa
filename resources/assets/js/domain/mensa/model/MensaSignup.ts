import User from "../../models/User";

export default interface MensaSignup {
    id: string,
    mensaId: string,
    user: User,
    email?: string,
    vegetarian: boolean,
    description: string,
    cook: boolean,
    dishwasher: boolean,
}

export type NewMensaSignup = Omit<MensaSignup, 'id'>;

export type UpdateMensaSignup = Partial<MensaSignup> & Pick<MensaSignup, 'id'>
