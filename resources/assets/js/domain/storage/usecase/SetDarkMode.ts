import repository from "../repository/StorageRepository";

export default function SetDarkMode(mode: boolean) {
    repository.setDarkMode(mode);
}