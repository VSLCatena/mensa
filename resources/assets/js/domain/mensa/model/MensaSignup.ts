import {User} from "../../common/model/User";
import FoodPreference from "./FoodPreference";

export default interface MensaSignup {
    id: string,
    foodPreference: FoodPreference|null,
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
    let foodPreference = ('foodPreference' in user ? user.foodPreference : null) ?? null;
    let description = ('description' in user ? user.description : "") ?? "";
    let allergies = ('allergies' in user ? user.allergies : "") ?? "";

    return {
        foodPreference: foodPreference,
        description: description,
        allergies: allergies,
        isIntro: isIntro,
        cook: false,
        dishwasher: false
    }
}

export type UpdateMensaSignup = Partial<MensaSignup> & Pick<MensaSignup, 'id'>
