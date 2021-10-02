import {AxiosResponse} from "axios";

export default async function MapResponse<T = any, R = AxiosResponse<T>>(response: R): Promise<T> {
    try {
        return Promise.resolve((response as unknown as AxiosResponse<T>).data as T);
    } catch (e) {
        return Promise.reject(e as Error);
    }
}