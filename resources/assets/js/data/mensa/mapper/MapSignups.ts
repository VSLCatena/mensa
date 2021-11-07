import {checkIsArray, requireNotNull} from "../../utils/MappingUtils";
import Result, {Failure, runCatching, Success} from "../../../domain/common/utils/Result";
import MensaSignup from "../../../domain/signup/model/MensaSignup";
import MensaSignupEntity from "../model/MensaSignupEntity";
import {MapFoodOption} from "../../common/MapFoodOption";

export default function MapSignups(data: MensaSignupEntity[]): Result<MensaSignup[]> {
    return runCatching(() => {
        checkIsArray('mensaSignup', data);
        return data.map(function(price: any) {
            return MapSignup(price).getOrThrow();
        });
    });
}

export function MapSignup(data: MensaSignupEntity): Result<MensaSignup> {
    return runCatching(() => {
        return {
            id: requireNotNull('id', data.id),
            isIntro: requireNotNull('isIntro', data.isIntro),
            foodOption: MapFoodOption(data.foodOption).getOrThrow(),
            extraInfo: requireNotNull('extraInfo', data.extraInfo),
            allergies: requireNotNull('allergies', data.allergies),
            cook: requireNotNull('cook', data.cook),
            dishwasher: requireNotNull('dishwasher', data.dishwasher),
        }
    });
}