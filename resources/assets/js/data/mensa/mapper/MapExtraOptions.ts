import {requireNotNull} from "../../utils/MappingUtils";
import ExtraOption from "../../../domain/mensa/model/ExtraOption";
import MensaExtraOptionsEntity from "../model/MensaExtraOptionsEntity";
import Result, {Failure, runCatching, Success} from "../../../domain/common/utils/Result";

export default function MapExtraOptions(data: MensaExtraOptionsEntity[]): Result<ExtraOption[]> {
    if (!Array.isArray(data))
        return new Failure(Error("data is not of type Array. ("+(typeof data)+")"));

    return new Success(data.map(function(price: any) {
        return MapPrice(price).getOrThrow();
    }));
}

export function MapPrice(data: MensaExtraOptionsEntity): Result<ExtraOption> {
    return runCatching(() => {
        return {
            id: requireNotNull('id', data.id),
            order: requireNotNull('order', data.order),
            description: requireNotNull('description', data.description),
            price: requireNotNull('price', data.price),
        }
    });
}