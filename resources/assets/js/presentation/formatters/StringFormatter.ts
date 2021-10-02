import Vue from 'vue';
import lang, {translatedText} from "../lang/Language";
import {SimpleUser} from "../../domain/common/model/User";
import Language from "../../domain/common/model/Language";

export function formatUsers(users: SimpleUser[], language: Language = Vue.prototype.$local.language): string|null {
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

    userText += " " + translatedText(language, lang.text.and) + " " + users[users.length-1].name;
    userText = userText.substr(2);

    return userText;
}
// user1, user2, user3 and user4

export function capitalize(text: string): string {
    return text.charAt(0).toUpperCase() + text.slice(1);
}