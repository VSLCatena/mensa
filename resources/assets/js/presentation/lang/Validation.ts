import {LanguageLintCheck} from "./LanguageText";

const validation = {
    general: {
        required: {
            nl: 'Veld is verplicht',
            en: 'Field is required',
        },
        max_length_reached: {
            nl: 'De maximum lengte van 191 characters is bereikt',
            en: 'The maximum length of 191 characters has been reached',
        }
    },
    email: {
        invalid: {
            nl: 'Moet een correcte email zijn',
            en: "Must be a valid email address",
        },
    },
}

// Make sure it is in the correct format
LanguageLintCheck(validation);

export default validation;