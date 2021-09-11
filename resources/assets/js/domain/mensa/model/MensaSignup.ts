import {User} from "../../common/model/User";

export default interface MensaSignup {
    id: string,
    vegetarian: boolean,
    isIntro: boolean,
    description: string,
    allergies: string,
    cook: boolean,
    dishwasher: boolean,
}

export type NewMensaSignup = Omit<MensaSignup, 'id'>;

export function createEmptySignup(
    mensaId: string,
    user: User,
    isIntro: boolean = false
): NewMensaSignup {
    let vegetarian = ('vegetarian' in user ? user.vegetarian : false) ?? false;
    let description = ('description' in user ? user.description : "") ?? "";
    let allergies = ('allergies' in user ? user.allergies : "") ?? "";

    return {
        vegetarian: vegetarian,
        description: description,
        allergies: allergies,
        isIntro: isIntro,
        cook: false,
        dishwasher: false
    }
}

export type UpdateMensaSignup = Partial<MensaSignup> & Pick<MensaSignup, 'id'>
