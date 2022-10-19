import {LanguageLintCheck} from './LanguageText';

export const Validation = {
  general: {
    required: {
      nl: 'Veld is verplicht',
      en: 'Field is required',
    },
    invalid: {
      nl: 'Veld verwacht specifieke waarde(s)',
      en: 'Field expects specific value(s)',
    },
    max_length_reached: {
      nl: 'De maximum lengte van 191 characters is bereikt',
      en: 'The maximum length of 191 characters has been reached',
    },
  },
  email: {
    invalid: {
      nl: 'Moet een correcte email zijn',
      en: 'Must be a valid email address',
    },
  },
};

// Make sure it is in the correct format
LanguageLintCheck(Validation);
