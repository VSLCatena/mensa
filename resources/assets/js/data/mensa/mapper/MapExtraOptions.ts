import {checkIsArray, requireNotNull} from "../../utils/MappingUtils";
import ExtraOption from "../../../domain/mensa/model/ExtraOption";
import MensaExtraOptionsEntity from "../model/MensaExtraOptionsEntity";
import Result, {runCatching} from "../../../domain/common/utils/Result";

export default function MapExtraOptions(data: MensaExtraOptionsEntity[]): Result<ExtraOption[]> {
    return runCatching(() => {
        checkIsArray('extraOptions', data);
        return data.map(function (price: any) {
            return MapPrice(price).getOrThrow();
        });
    });
}

export function MapPrice(data: MensaExtraOptionsEntity): Result<ExtraOption> {
    return runCatching(() => {
        return {
            id: requireNotNull('id', data.id),
            description: requireNotNull('description', data.description),
            price: requireNotNull('price', data.price),
        }
    });
}