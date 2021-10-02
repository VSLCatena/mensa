export default function WithAuthHeader(token: string): { Authorization: string } {
    return { "Authorization": `Bearer ${token}` };
}