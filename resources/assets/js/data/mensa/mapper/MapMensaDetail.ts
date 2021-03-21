import Result, {runCatching} from "../../../utils/Result";
import {requireNotNull} from "../../utils/MappingUtils";
import MapPrices from "./MapPrices";
import MensaDetail from "../../../domain/mensa/model/MensaDetail";
import MensaDetailEntity from "../model/MensaDetailEntity";
import MapSignups from "./MapSignups";

export function MapMensaDetail(data: MensaDetailEntity): Result<MensaDetail> {
    return runCatching(() => {
        return {
            id: requireNotNull('id', data.id),
            title: requireNotNull('title', data.title),
            description: requireNotNull('description', data.description),
            prices: MapPrices(requireNotNull('prices', data.prices)).getOrThrow(),
            date: requireNotNull('date', data.date),
            closeTime: requireNotNull('closeTime', data.closeTime),
            maxSignups: requireNotNull('maxSignups', data.maxSignups),
            users: MapSignups(requireNotNull('users', data.users)).getOrThrow(),
        }
    });
}