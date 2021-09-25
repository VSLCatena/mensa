import $lang, {CurrentLanguage, translatedText} from "../lang/Language";
import FoodPreference from "../../domain/mensa/model/FoodPreference";

export const MAX_STRING_LENGTH = 191
export const EmailRule = /^(?!\.)("([^"\r\\]|\\["\r\\])*"|([-a-z0-9!#$%&'*+\/=?^_`{|}~]|(?<!\.)\.)*)(?<!\.)@[a-z0-9][\w\.-]*[a-z0-9]\.[a-z][a-z\.]*[a-z]$/;

const MaxStringLengthValidation =
    (value: string|null) => {
        return (!value || value.length <= MAX_STRING_LENGTH) ||
            translatedText(CurrentLanguage.language, $lang.validation.general.max_length_reached)
    };

export const Validations = {
    email: [
        (value: string|null) => {
            return !!value || translatedText(CurrentLanguage.language, $lang.validation.general.required)
        },
        (value: string|null) => {
            return (value && EmailRule.test(value)) ||
                translatedText(CurrentLanguage.language, $lang.validation.email.invalid)
        },
        MaxStringLengthValidation
    ],
    foodOptions: [
        (value: string) => {
            return (
                value == FoodPreference.VEGAN ||
                value == FoodPreference.VEGETARIAN ||
                value == FoodPreference.MEAT
                ) || translatedText(CurrentLanguage.language, $lang.validation.general.required)
        }
    ],
    allergies: [
        MaxStringLengthValidation
    ],
    description: [
        MaxStringLengthValidation
    ]
}