import Mensa from "../../../domain/mensa/model/Mensa";
import MensaEntity from "../model/MensaEntity";
import {requireNotNull} from "../../utils/MappingUtils";
import MapExtraOptions from "./MapExtraOptions";
import Result, {Failure, runCatching, Success} from "../../../utils/Result";
import MapSimpleUsers from "./MapSimpleUsers";

export default function MapMensas(data: MensaEntity[]): Result<Mensa[]> {
    if (!Array.isArray(data))
        return new Failure(Error("data is not of type Array. ("+(typeof data)+")"));

    return new Success(data.map(function(price: any) {
        return MapMensa(price).getOrThrow();
    }));
}

export function MapMensa(data: MensaEntity): Result<Mensa> {
    return runCatching(() => {
        console.debug(data.date);
        console.debug(new Date(requireNotNull("sdf", data.date)))
        return {
            id: requireNotNull('id', data.id),
            title: requireNotNull('title', data.title),
            description: requireNotNull('description', data.description),
            extraOptions: MapExtraOptions(requireNotNull('extraOptions', data.extraOptions)).getOrThrow(),
            date: new Date(requireNotNull('date', data.date) * 1000),
            closingTime: new Date(requireNotNull('closingTime', data.closingTime) * 1000),
            maxSignups: requireNotNull('maxSignups', data.maxSignups),
            signups: requireNotNull('signups', data.signups),
            cooks: MapSimpleUsers(requireNotNull('cooks', data.cooks)).getOrThrow(),
            dishwashers: requireNotNull('dishwashers', data.dishwashers),
        }
    });
}