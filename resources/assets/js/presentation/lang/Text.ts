import {LanguageLintCheck} from "./LanguageText";

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
        },
        save: {
            nl: 'Opslaan',
            en: 'Save',
        },
        yes: {
            nl: 'Ja',
            en: 'Yes',
        },
        no: {
            nl: 'Nee',
            en: 'No',
        },
        cancel: {
            nl: 'Annuleer',
            en: 'Cancel',
        }
    },
    and: {
        nl: "en",
        en: "and"
    },
    menu: {
        account: {
            nl: "Account",
            en: "Account",
        },
        switch_language: {
            nl: "Switch to English",
            en: "Wissel naar Nederlands",
        },
        switch_theme: {
            to_dark: {
                nl: "Wissel naar donker thema",
                en: "Switch to dark theme",
            },
            to_light: {
                nl: "Wissel naar licht thema",
                en: "Switch to light theme",
            }
        }
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
        dishwasher_count: {
            zero: {
                nl: '%s afwassers',
                en: '%s dishwashers',
            },
            one: {
                nl: '%s afwasser',
                en: '%s dishwasher',
            },
            many: {
                nl: '%s afwassers',
                en: '%s dishwashers',
            }
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
    login: {
        title: {
            nl: "Login",
            en: "Login",
        },
        login_button: {
            nl: 'Klik hier om in te loggen',
            en: 'Click here to log in',
        },
        logout_button: {
            nl: 'Log uit',
            en: 'Log out',
        },
        login_info: {
            nl: 'Inloggen verloopt via Microsoft. Dit doe je door in te loggen met <accountnaam>@vslcatena.nl en je Catena wachtwoord.',
            en: 'Logging in happens through Microsoft. You can do this by logging in with <accountname>@vslcatena.nl and your Catena password',
        },
        forgot_password: {
            nl: "Wachtwoord vergeten",
            en: "Forgot password",
        },
        logging_in: {
            nl: "Logging in",
            en: "Logging in",
        },
        error: {
            nl: "Er ging iets in bij het inloggen, probeer het later opnieuw.",
            en: "Something went wrong trying to log in, try again later",
        }
    },
    profile: {
        title: {
            nl: 'Hey',
            en: 'Hey',
        }
    },
    logout: {
        title: {
            nl: 'Uitloggen',
            en: 'Log out',
        },
        confirm: {
            nl: 'Weet je zeker dat je wilt uitloggen?',
            en: 'Are you sure you want to log out?',
        },
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
        field_allergies: {
            nl: 'Allergieën',
            en: 'Allergies',
        },
        field_extraInfo: {
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
    },
    foodOptions: {
        none: {
            nl: 'Geen',
            en: 'None',
        },
        vegan: {
            nl: 'Veganistisch',
            en: 'Vegan',
        },
        vegetarian: {
            nl: 'Vegetarisch',
            en: 'Vegetarian',
        },
        meat: {
            nl: 'Vlees',
            en: 'Meat',
        },
    }
}

// Make sure it is in the correct format
LanguageLintCheck(text)

export default text;