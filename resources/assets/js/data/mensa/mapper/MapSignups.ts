import {requireNotNull} from "../../utils/MappingUtils";
import Result, {Failure, runCatching, Success} from "../../../utils/Result";
import MensaSignup from "../../../domain/mensa/model/MensaSignup";
import MensaSignupEntity from "../model/MensaSignupEntity";
import {MapFoodPreference} from "./MapFoodPreference";

export default function MapSignups(data: MensaSignupEntity[]): Result<MensaSignup[]> {
    if (!Array.isArray(data))
        return new Failure(Error("data is not of type Array. ("+(typeof data)+")"));

    return new Success(data.map(function(price: any) {
        return MapSignup(price).getOrThrow();
    }));
}

export function MapSignup(data: MensaSignupEntity): Result<MensaSignup> {
    return runCatching(() => {
        return {
            id: requireNotNull('id', data.id),
            isIntro: requireNotNull('isIntro', data.isIntro),
            foodPreference: MapFoodPreference(data.foodPreference).getOrThrow(),
            description: requireNotNull('description', data.description),
            allergies: requireNotNull('allergies', data.allergies),
            cook: requireNotNull('cook', data.cook),
            dishwasher: requireNotNull('dishwasher', data.dishwasher),
        }
    });
}