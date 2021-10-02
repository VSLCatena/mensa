import repository from "../repository/StorageRepository";

export default function GetUserToken(): string|undefined {
    return repository.getUserToken();
}