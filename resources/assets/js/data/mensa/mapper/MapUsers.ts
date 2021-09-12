import Result, {Failure, runCatching, Success} from "../../../utils/Result";
import UserEntity from "../model/UserEntity";
import {requireNotNull} from "../../utils/MappingUtils";
import {User} from "../../../domain/common/model/User";

export default function MapUsers(data: UserEntity[]): Result<User[]> {
    if (!Array.isArray(data))
        return new Failure(Error("data is not of type Array. ("+(typeof data)+")"));

    return new Success(data.map(function(price: any) {
        return MapUser(price).getOrThrow();
    }));
}

export function MapUser(data: UserEntity): Result<User> {
    return runCatching(() => {
        return {
            id: requireNotNull('id', data.id),
            name: requireNotNull('name', data.name),
            email: requireNotNull('email', data.email),
            isAdmin: requireNotNull('isAdmin', data.isAdmin)
        }
    });
}