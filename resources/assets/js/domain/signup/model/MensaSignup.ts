import FoodOption from "../../mensa/model/FoodOption";

export default interface MensaSignup {
    foodOption: FoodOption|null,
    isIntro: boolean,
    extraInfo: string,
    allergies: string,
    cook?: boolean,
    dishwasher?: boolean,
}
