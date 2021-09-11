export interface FullUser extends SimpleUser, UserEmail {
    id: string,
    name: string,
    email: string,
    isAdmin: boolean,
    vegetarian: boolean,
    description: string,
    allergies: string,
}

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
    vegetarian?: boolean,
    description?: string,
    allergies?: string,
}

export type AuthUser = FullUser | Anonymous

export interface Anonymous extends User {
    isAdmin: false,
}

export let AnonymousUser: Anonymous = {
    isAdmin: false
}