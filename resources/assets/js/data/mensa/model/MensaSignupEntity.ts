import UserEntity from "./UserEntity";

export default interface MensaSignupEntity {
    id?: string,
    mensaId?: string,
    user?: UserEntity,
    email?: string,
    vegetarian?: boolean,
    description?: string,
    cook?: boolean,
    dishwasher?: boolean,
}