import Lang, {CurrentLanguage, Language} from "../../lang/Language";
import {formatOrdinal} from "./NumberFormatter";

export interface Options {
    withDay: boolean;
    preferShort: boolean;
    yearAlways: boolean;
    withDate: boolean;
    withTime: boolean;
}

let defaultOptions: Options = {
    withDay: true,
    preferShort: false,
    yearAlways: false,
    withDate: true,
    withTime: true,
};

export function formatDate(
    date: Date,
    options: Partial<Options>|null = null,
    language: Language = CurrentLanguage.language,
): string {
    let actualOptions = {...defaultOptions, ...options }

    let currentDate = new Date();

    let builder: string[] = [];

    if (actualOptions.withDate) {
        let dateBuilder: string[] = [];
        // Monday
        if (actualOptions.withDay) {
            dateBuilder.push(getDay(date, language, actualOptions.preferShort));
        }

        // 30
        let dayText: string = date.getDate().toString();
        if (language.language == "en") dayText = formatOrdinal(date.getDate())
        dateBuilder.push(dayText);

        // of May
        let monthText: string = getMonth(date, language, actualOptions.preferShort);
        if (language.language == "en") monthText = "of "+monthText;
        dateBuilder.push(monthText);

        // 2023
        if (actualOptions.yearAlways || currentDate.getFullYear() != date.getFullYear()) {
            dateBuilder.push(date.getFullYear().toString());
        }

        let dateText = dateBuilder.join(' ');
        // ','
        if (actualOptions.withTime) {
            dateText += ",";
        }

        builder.push(dateText);
    }

    // 12:30
    if (actualOptions.withTime) {
        builder.push(date.getHours().toString().padStart(2, '0') + ":" + date.getMinutes().toString().padStart(2, '0'));
    }

    return builder.join(" ");
}

// NL: Maandag 30 mei 2023, 12:30
// EN: Monday 30th of May 2023, 12:30

function getDay(date: Date, language: Language, preferShort: boolean = false): string {
    let days = preferShort ? Lang.date.days.short : Lang.date.days.long;
    switch (date.getDay()) {
        case 0:
            return language.getText(days.sunday);
        case 1:
            return language.getText(days.monday);
        case 2:
            return language.getText(days.tuesday);
        case 3:
            return language.getText(days.wednesday);
        case 4:
            return language.getText(days.thursday);
        case 5:
            return language.getText(days.friday);
        case 6:
            return language.getText(days.saturday);
        default:
            throw new Error("Unknown day");
    }
}

function getMonth(date: Date, language: Language, preferShort: boolean = false): string {
    let months = preferShort ? Lang.date.months.short : Lang.date.months.long;

    switch (date.getMonth()) {
        case 0:
            return language.getText(months.january);
        case 1:
            return language.getText(months.february);
        case 2:
            return language.getText(months.march);
        case 3:
            return language.getText(months.april);
        case 4:
            return language.getText(months.may);
        case 5:
            return language.getText(months.june);
        case 6:
            return language.getText(months.july);
        case 7:
            return language.getText(months.august);
        case 8:
            return language.getText(months.september);
        case 9:
            return language.getText(months.october);
        case 10:
            return language.getText(months.november);
        case 11:
            return language.getText(months.december);
        default:
            throw new Error("Unknown month");
    }
}