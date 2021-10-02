import repository from "../repository/StorageRepository";

export default function SetUserToken(token: string|null) {
    repository.setUserToken(token);
}