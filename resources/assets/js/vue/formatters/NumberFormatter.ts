import {CurrentLanguage, Language} from "../../lang/Language";


export function formatOrdinal(num: number, language: Language = CurrentLanguage.language): string {
    switch (language.language) {
        case "nl":
            return formatOrdinalNL(num)
        case "en":
            return formatOrdinalEN(num)
    }
}

function formatOrdinalNL(num: number): string {
    return num + "ᵉ";
}

function formatOrdinalEN(num: number): string {
    switch (num % 100) {
        case 11:
        case 12:
        case 13:
            return num + "th";
    }

    switch (num % 10) {
        case 1:
            return num + "st";
        case 2:
            return num + "nd";
        case 3:
            return num + "rd";
        default:
            return num + "th";
    }
}