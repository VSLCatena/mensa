export function OptionalAuthHeader(token: string | null): { Authorization?: string } {
    if (token == null) return {};
    return WithAuthHeader(token);
}

export default function WithAuthHeader(token: string): { Authorization: string } {
    return {"Authorization": `Bearer ${token}`};
}