import User from "../../user/model/User";

export default interface MensaSignup {
    user: User,
    mensaId: string,
    vegetarian: boolean,
    description: string,
    cook: boolean,
    dishwasher: boolean,
}

export type NewMensaSignup = Omit<MensaSignup, 'id'>;

export type UpdateMensaSignup = Partial<MensaSignup> & Pick<MensaSignup, 'user'>
