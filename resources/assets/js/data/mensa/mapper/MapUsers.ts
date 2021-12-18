import Result, {runCatching} from "../../../domain/common/utils/Result";
import UserEntity from "../../user/model/UserEntity";
import {checkIsArray, requireNotNull} from "../../utils/MappingUtils";
import {FullUser, SimpleUser, User} from "../../../domain/common/model/User";
import MensaSimpleUserEntity from "../model/MensaSimpleUserEntity";
import FullUserEntity from "../../user/model/FullUserEntity";
import {MapFoodOption} from "../../common/MapFoodOption";


export function MapFullUser(data: FullUserEntity): Result<FullUser> {
    return runCatching(() => {
        return {
            id: requireNotNull('id', data.id),
            name: requireNotNull('name', data.name),
            email: requireNotNull('email', data.email),
            isAdmin: requireNotNull('isAdmin', data.isAdmin),
            foodPreference: MapFoodOption(data.foodPreference).getOrNull(),
            extraInfo: data.extraInfo ?? null,
            allergies: data.allergies ?? null
        }
    })
}

export function MapUsers(data: UserEntity[]): Result<User[]> {
    return runCatching(() => {
        checkIsArray('users', data);
        return data.map(function (price: any) {
            return MapUser(price).getOrThrow();
        });
    });
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
    return runCatching(() => {
        checkIsArray('mensaSimpleUser', data);
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