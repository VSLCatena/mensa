import {requireNotNull} from "../../utils/MappingUtils";
import ExtraOption from "../../../domain/mensa/model/ExtraOption";
import MensaExtraOptionsEntity from "../model/MensaExtraOptionsEntity";
import Result, {Failure, runCatching, Success} from "../../../utils/Result";
import MensaMenuEntity from "../model/MensaMenuEntity";
import MensaMenuItem from "../../../domain/mensa/model/MensaMenuItem";

export default function MapMenu(data: MensaMenuEntity[]): Result<MensaMenuItem[]> {
    if (!Array.isArray(data))
        return new Failure(Error("data is not of type Array. ("+(typeof data)+")"));

    return new Success(data.map(function(price: any) {
        return MapMenuItem(price).getOrThrow();
    }));
}

export function MapMenuItem(data: MensaMenuEntity): Result<MensaMenuItem> {
    return runCatching(() => {
        return {
            id: requireNotNull('id', data.id),
            order: requireNotNull('order', data.order),
            text: requireNotNull('description', data.text),
        }
    });
}