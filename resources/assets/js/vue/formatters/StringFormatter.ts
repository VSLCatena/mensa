import SimpleUser from "../../domain/mensa/model/SimpleUser";
import lang, {CurrentLanguage, Language} from "../../lang/Language";

export function formatUsers(users: SimpleUser[], language: Language = CurrentLanguage.language): string|null {
    switch (users.length) {
        case 0:
            return null;
        case 1:
            return users[0].name;
    }

    let userText = "";

    for (let x = 0; x < users.length - 1; x++) {
        userText += ", " + users[x].name;
    }

    userText += " " + language.getText(lang.text.and) + " " + users[users.length-1].name;
    userText = userText.substr(2);

    return userText;
}
// user1, user2, user3 and user4

export function capitalize(text: string): string {
    return text.charAt(0).toUpperCase() + text.slice(1);
}