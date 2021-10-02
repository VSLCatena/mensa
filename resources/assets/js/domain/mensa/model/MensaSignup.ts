import {User} from "../../common/model/User";
import FoodPreference from "./FoodPreference";

export default interface MensaSignup {
    id: string,
    foodPreference: FoodPreference|null,
    isIntro: boolean,
    extraInfo: string,
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
    let foodPreference: FoodPreference|null = null;
    let extraInfo: string = "";
    let allergies: string = "";

    if (!isIntro) {
        foodPreference = ('foodPreference' in user ? user.foodPreference : null) ?? null;
        extraInfo = ('extraInfo' in user ? user.extraInfo : "") ?? "";
        allergies = ('allergies' in user ? user.allergies : "") ?? "";
    }

    return {
        foodPreference: foodPreference,
        extraInfo: extraInfo,
        allergies: allergies,
        isIntro: isIntro,
        cook: false,
        dishwasher: false
    }
}

export type UpdateMensaSignup = Partial<MensaSignup> & Pick<MensaSignup, 'id'>
