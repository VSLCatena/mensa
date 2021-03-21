import {requireNotNull} from "../../utils/MappingUtils";
import ExtraOption from "../../../domain/mensa/model/ExtraOption";
import PriceEntity from "../model/PriceEntity";
import Result, {Failure, runCatching, Success} from "../../../utils/Result";

export default function MapPrices(data: PriceEntity[]): Result<ExtraOption[]> {
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
            name: requireNotNull('name', data.name),
            price: requireNotNull('price', data.price),
        }
    });
}