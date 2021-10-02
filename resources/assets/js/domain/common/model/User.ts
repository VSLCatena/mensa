import FoodPreference from "../../mensa/model/FoodPreference";

export interface FullUser extends SimpleUser, UserEmail {
    id: string,
    name: string,
    email: string,
    isAdmin: boolean,
    foodPreference: FoodPreference|null,
    extraInfo: string|null,
    allergies: string|null,
}

export type UpdatableUser = Partial<Omit<FullUser, 'id' | 'name' | 'email' | 'isAdmin'>>;

export interface SimpleUser extends User {
    id: string,
    name: string,
}

export interface UserEmail extends User {
    email: string,
}

export interface User {
    id?: string,
    name?: string,
    email?: string,
    isAdmin?: boolean,
    foodPreference?: FoodPreference|null,
    extraInfo?: string|null,
    allergies?: string|null,
}

export type AuthUser = FullUser | Anonymous

export interface Anonymous extends User {
    isAdmin: false,
}

export let AnonymousUser: Anonymous = {
    isAdmin: false
}