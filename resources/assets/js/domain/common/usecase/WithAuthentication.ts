import GetUserToken from "../../storage/usecase/GetUserToken";

export default async function WithAuthentication<T>(
    call: (token: string|null) => T,
    strategy: Strategy = Strategy.AUTH_REQUIRED
): Promise<T> {
    let token = await GetUserToken() ?? null;
    if (token == null && strategy == Strategy.AUTH_REQUIRED) {
        return Promise.reject(Error("Authentication required"));
    }

    return call(token);
}

export enum Strategy {
    AUTH_REQUIRED,
    AUTH_OPTIONAL,
}