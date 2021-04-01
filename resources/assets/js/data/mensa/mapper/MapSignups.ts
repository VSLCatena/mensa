import {requireNotNull} from "../../utils/MappingUtils";
import Result, {Failure, runCatching, Success} from "../../../utils/Result";
import MensaSignup from "../../../domain/mensa/model/MensaSignup";
import {MapUser} from "./MapUsers";
import MensaSignupEntity from "../model/MensaSignupEntity";

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
            user: MapUser(requireNotNull('user', data.user)).getOrThrow(),
            mensaId: requireNotNull('mensaId', data.mensaId),
            vegetarian: requireNotNull('vegetarian', data.vegetarian),
            description: requireNotNull('description', data.description),
            cook: requireNotNull('cook', data.cook),
            dishwasher: requireNotNull('dishwasher', data.dishwasher),
        }
    });
}