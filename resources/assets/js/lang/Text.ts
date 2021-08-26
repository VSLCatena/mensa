import {LanguageLintCheck} from "./LanguageTypes";

const text = {
    and: {
        nl: "en",
        en: "and"
    },
    mensa: {
        mensas_between: {
            nl: "Mensas tussen",
            en: "Mensas between",
        },
        next_weeks: {
            nl: "+2 weken",
            en: "+2 weeks",
        },
        previous_weeks: {
            nl: "-2 weken",
            en: "+2 weeks",
        },
        signups: {
            nl: "Inschrijvingen",
            en: "Signups",
        },
        closingtime: {
            nl: "Sluittijd",
            en: "Closing time",
        },
        price: {
            nl: "Prijs",
            en: "Price",
        },
        cooked_by: {
            nl: "Gekookt door",
            en: "Cooked by",
        },
        dishwashers: {
            nl: "Afwassers",
            en: "Dishwashers",
        },
        button_signup: {
            nl: "Inschrijven",
            en: "Sign up",
        },
        button_overview: {
            nl: "Overzicht",
            en: "Overview",
        }
    }
}

// Make sure it is in the correct format
LanguageLintCheck(text)

export default text;