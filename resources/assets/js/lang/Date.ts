import {LanguageLintCheck} from "./LanguageTypes";
import text from "./Text";

let days = {
    short: {
        monday: {
            nl: "ma",
            en: "Mon.",
        },
        tuesday: {
            nl: "di",
            en: "Tue.",
        },
        wednesday: {
            nl: "wo",
            en: "Wed.",
        },
        thursday: {
            nl: "do",
            en: "Thu.",
        },
        friday: {
            nl: "vr",
            en: "Fri.",
        },
        saturday: {
            nl: "za",
            en: "Sat.",
        },
        sunday: {
            nl: "zo",
            en: "Sun.",
        }
    },
    long: {
        monday: {
            nl: "maandag",
            en: "Monday",
        },
        tuesday: {
            nl: "dinsdag",
            en: "Tuesday",
        },
        wednesday: {
            nl: "woensdag",
            en: "Wednesday",
        },
        thursday: {
            nl: "donderdag",
            en: "Thursday",
        },
        friday: {
            nl: "vrijdag",
            en: "Friday",
        },
        saturday: {
            nl: "zaterdag",
            en: "Saturday",
        },
        sunday: {
            nl: "zondag",
            en: "Sunday",
        }
    }
}

let months = {
    short: {
        january: {
            nl: "jan",
            en: "Jan.",
        },
        february: {
            nl: "feb",
            en: "Feb.",
        },
        march: {
            nl: "mrt",
            en: "Mar.",
        },
        april: {
            nl: "apr",
            en: "Apr.",
        },
        may: {
            nl: "mei",
            en: "May",
        },
        june: {
            nl: "jun",
            en: "June",
        },
        july: {
            nl: "jul",
            en: "July",
        },
        august: {
            nl: "aug",
            en: "Aug",
        },
        september: {
            nl: "sep",
            en: "Sep.",
        },
        october: {
            nl: "okt",
            en: "Oct.",
        },
        november: {
            nl: "nov",
            en: "Nov.",
        },
        december: {
            nl: "dec",
            en: "Dec.",
        }
    },
    long: {
        january: {
            nl: "januari",
            en: "January",
        },
        february: {
            nl: "februari",
            en: "February",
        },
        march: {
            nl: "maart",
            en: "March",
        },
        april: {
            nl: "april",
            en: "April",
        },
        may: {
            nl: "mei",
            en: "May",
        },
        june: {
            nl: "juni",
            en: "June",
        },
        july: {
            nl: "juli",
            en: "July",
        },
        august: {
            nl: "augustus",
            en: "Augustus",
        },
        september: {
            nl: "september",
            en: "September",
        },
        october: {
            nl: "oktober",
            en: "October",
        },
        november: {
            nl: "november",
            en: "November",
        },
        december: {
            nl: "december",
            en: "December",
        }
    }
}

const combined = {
    at: {
        nl: "om",
        en: "at",
    },
    days: days,
    months: months
}

// Make sure it is in the correct format
LanguageLintCheck(text)

export default combined;