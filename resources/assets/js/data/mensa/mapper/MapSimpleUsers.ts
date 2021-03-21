import Result, {Failure, runCatching, Success} from "../../../utils/Result";
import SimpleUser from "../../../domain/mensa/model/SimpleUser";
import MensaSimpleUserEntity from "../model/MensaSimpleUserEntity";
import {requireNotNull} from "../../utils/MappingUtils";

export default function MapSimpleUsers(data: MensaSimpleUserEntity[]): Result<SimpleUser[]> {
    if (!Array.isArray(data))
        return new Failure(Error("data is not of type Array. ("+(typeof data)+")"));

    return new Success(data.map(function(price: any) {
        return MapSimpleUser(price).getOrThrow();
    }));
}

export function MapSimpleUser(data: MensaSimpleUserEntity): Result<SimpleUser> {
    return runCatching(() => {
        return {
            id: requireNotNull('id', data.id),
            name: requireNotNull('name', data.name)
        }
    });
}