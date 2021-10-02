import Mensa from "../../../domain/mensa/model/Mensa";
import MensaEntity from "../model/MensaEntity";
import {requireNotNull} from "../../utils/MappingUtils";
import MapExtraOptions from "./MapExtraOptions";
import Result, { runCatching } from "../../../domain/common/utils/Result";
import MapSimpleUsers from "./MapSimpleUsers";
import MensaListEntity, {BetweenEntity} from "../model/MensaListEntity";
import MensaList, {Between} from "../../../domain/mensa/model/MensaList";
import MapDate from "./MapDate";
import MapMenu from "./MapMenu";
import {MapFoodPreferences} from "../../common/MapFoodPreference";


export default function MapMensaList(data: MensaListEntity): Result<MensaList> {
    return runCatching(() => {
        return {
            between: MapBetween(requireNotNull("between", data.between)).getOrThrow(),
            mensas: MapMensas(requireNotNull("mensas", data.mensas)).getOrThrow()
        }
    });
}

export function MapBetween(data: BetweenEntity): Result<Between> {
    return runCatching(() => {
        return {
            start: MapDate(requireNotNull("start", data.start)),
            end: MapDate(requireNotNull("end", data.end))
        }
    })
}

function MapMensas(data: MensaEntity[]): Result<Mensa[]> {
    return runCatching(() => {
        if (!Array.isArray(data))
            throw new Error("data is not of type Array. ("+(typeof data)+")");

        return data.map(function(price: any) {
            return MapMensa(price).getOrThrow();
        });
    })
}

function MapMensa(data: MensaEntity): Result<Mensa> {
    return runCatching(() => {
        return {
            id: requireNotNull('id', data.id),
            title: requireNotNull('title', data.title),
            description: requireNotNull('description', data.description),
            foodOptions: MapFoodPreferences(requireNotNull('foodOptions', data.foodOptions)).getOrThrow(),
            menu: MapMenu(requireNotNull('menu', data.menu)).getOrThrow(),
            extraOptions: MapExtraOptions(requireNotNull('extraOptions', data.extraOptions)).getOrThrow(),
            date: MapDate(requireNotNull('date', data.date)),
            closingTime: MapDate(requireNotNull('closingTime', data.closingTime)),
            maxSignups: requireNotNull('maxSignups', data.maxSignups),
            price: requireNotNull("price", data.price),
            signups: requireNotNull('signups', data.signups),
            cooks: MapSimpleUsers(requireNotNull('cooks', data.cooks)).getOrThrow(),
            dishwashers: requireNotNull('dishwashers', data.dishwashers),
        }
    });
}