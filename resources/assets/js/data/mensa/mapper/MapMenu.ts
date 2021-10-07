import {checkIsArray, requireNotNull} from "../../utils/MappingUtils";
import ExtraOption from "../../../domain/mensa/model/ExtraOption";
import MensaExtraOptionsEntity from "../model/MensaExtraOptionsEntity";
import Result, {Failure, runCatching, Success} from "../../../domain/common/utils/Result";
import MensaMenuEntity from "../model/MensaMenuEntity";
import MensaMenuItem from "../../../domain/mensa/model/MensaMenuItem";

export default function MapMenu(data: MensaMenuEntity[]): Result<MensaMenuItem[]> {
    return runCatching(() => {
        checkIsArray('mensaMenu', data);
        return data.map(function(price: any) {
            return MapMenuItem(price).getOrThrow();
        });
    });
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