import {requireNotNull} from "../../utils/MappingUtils";
import ExtraOption from "../../../domain/mensa/model/ExtraOption";
import PriceEntity from "../model/PriceEntity";
import Result, {Failure, runCatching, Success} from "../../../utils/Result";

export default function MapExtraOptions(data: PriceEntity[]): Result<ExtraOption[]> {
    if (!Array.isArray(data))
        return new Failure(Error("data is not of type Array. ("+(typeof data)+")"));

    return new Success(data.map(function(price: any) {
        return MapPrice(price).getOrThrow();
    }));
}

export function MapPrice(data: PriceEntity): Result<ExtraOption> {
    return runCatching(() => {
        return {
            id: requireNotNull('id', data.id),
            description: requireNotNull('description', data.description),
            price: requireNotNull('price', data.price),
        }
    });
}