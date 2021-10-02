import Result, {Failure, runCatching, Success} from "../../../domain/common/utils/Result";
import UserEntity from "../../user/model/UserEntity";
import {requireNotNull} from "../../utils/MappingUtils";
import {FullUser, SimpleUser, User} from "../../../domain/common/model/User";
import MensaSimpleUserEntity from "../model/MensaSimpleUserEntity";
import FullUserEntity from "../../user/model/FullUserEntity";
import {MapFoodPreference} from "../../common/MapFoodPreference";



export function MapFullUser(data: FullUserEntity): Result<FullUser> {
    return runCatching(() => {
        return {
            id: requireNotNull('id', data.id),
            name: requireNotNull('name', data.name),
            email: requireNotNull('email', data.email),
            isAdmin: requireNotNull('isAdmin', data.isAdmin),
            foodPreference: MapFoodPreference(data.foodPreference).getOrNull(),
            extraInfo: data.extraInfo ?? null,
            allergies: data.allergies ?? null
        }
    })
}

export function MapUsers(data: UserEntity[]): Result<User[]> {
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


export function MapSimpleUsers(data: MensaSimpleUserEntity[]): Result<SimpleUser[]> {
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