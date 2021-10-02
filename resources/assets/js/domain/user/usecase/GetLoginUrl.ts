import repository from "../repository/UserRepository";

export default async function GetLoginUrl(): Promise<string> {
    return repository.getUrl();
}