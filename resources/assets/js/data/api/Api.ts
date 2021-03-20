import { URL_PATH } from '../../config';

var apiKey: string|null = "";
export function setApiKey(key?: string) {
    apiKey = key ?? null;
}

class Api {
    
    GET(path: string, ...args: any): Promise<Response> {
        return this.call("GET", path, args);
    }

    POST(path: string, ...args: any): Promise<Response> {
        return this.call("POST", path, args);
    }

    PATCH(path: string, ...args: any): Promise<Response> {
        return this.call("PATCH", path, args);
    }

    PUT(path: string, ...args: any): Promise<Response> {
        return this.call("PUT", path, args);
    }

    DELETE(path: string, ...args: any): Promise<Response> {
        return this.call("DELETE", path, args);
    }

    private call(method: string, path: string, ...args: any): Promise<Response> {
        return fetch(URL_PATH + path, {
            method: method,
            cache: "no-cache",
            headers: {
                "Content-Type": "application/json",
                "Authorization": "Bearer " + apiKey,
            },
            body: JSON.stringify(args),
        })
    }
}

const api = new Api();
export default api;