import Result, {runCatching} from "../../../domain/common/utils/Result";
import MensaSimpleUserEntity from "../model/MensaSimpleUserEntity";
import {checkIsArray, requireNotNull} from "../../utils/MappingUtils";
import {SimpleUser} from "../../../domain/common/model/User";

export default function MapSimpleUsers(data: MensaSimpleUserEntity[]): Result<SimpleUser[]> {
    return runCatching(() => {
        checkIsArray('simpleUsers', data);
        return data.map(function (price: any) {
            return MapSimpleUser(price).getOrThrow();
        });
    });
}

export function MapSimpleUser(data: MensaSimpleUserEntity): Result<SimpleUser> {
    return runCatching(() => {
        return {
            id: requireNotNull('id', data.id),
            name: requireNotNull('name', data.name)
        }
    });
}