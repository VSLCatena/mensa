import {LanguageLintCheck} from "./LanguageTypes";

const text = {
    general: {
        close: {
            nl: "Sluit",
            en: "Close",
        },
        next: {
            nl: 'Volgende',
            en: 'Next',
        },
        previous: {
            nl: 'Vorige',
            en: 'Previous',
        }
    },
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
        },
        menu_title: {
            nl: "Menu",
            en: "Menu",
        },
        extra_options_title: {
            nl: "Extra opties",
            en: "Extra options",
        }
    },
    signup: {
        mensa_at: {
            nl: "Inschrijving voor mensa op",
            en: "Singup for mensa at",
        },
        tab_signup: {
            nl: "Inschrijving",
            en: "Signup",
        },
        tab_intro: {
            nl: "Intro",
            en: "Intro",
        },
        add_intro: {
            nl: '+ intro',
            en: '+ intro',
        },
        remove_intro: {
            nl: 'Verwijder intro',
            en: 'Delete intro',
        },
        button_signup: {
            nl: 'Inschrijven',
            en: 'Sign up',
        },
        field_email: {
            nl: 'Email opgegeven bij Catena',
            en: 'Email registered at Catena',
        },
        field_food_preference: {
            nl: 'Etens voorkeur',
            en: 'Food preference',
        },
        field_food_vegan: {
            nl: 'Veganistisch',
            en: 'Vegan',
        },
        field_food_vegetarian: {
            nl: 'Vegetarisch',
            en: 'Vegetarian',
        },
        field_food_meat: {
            nl: 'Vlees',
            en: 'Meat',
        },
        field_allergies: {
            nl: 'Allergieën',
            en: 'Allergies',
        },
        field_description: {
            nl: 'Extra info',
            en: 'Extra information',
        },
        field_cook: {
            nl: 'Is koker',
            en: 'Is cook',
        },
        field_dishwasher: {
            nl: 'Vrijwillig afwassen',
            en: 'Voluntair for washing dishes',
        },
    }
}

// Make sure it is in the correct format
LanguageLintCheck(text)

export default text;