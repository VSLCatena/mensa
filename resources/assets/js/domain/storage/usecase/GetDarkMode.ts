import repository from "../repository/StorageRepository";

export default function GetDarkMode(): boolean {
    return repository.getDarkMode();
}