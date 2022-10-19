import Ajv from 'ajv';

// TODO AJV injection
export const ajv = new Ajv({code: {es5: true}, unicodeRegExp: false});
