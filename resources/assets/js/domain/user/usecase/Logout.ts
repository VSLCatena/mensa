import SetUserToken from "../../storage/usecase/SetUserToken";

export default async function Logout() {
    return SetUserToken(null);
}