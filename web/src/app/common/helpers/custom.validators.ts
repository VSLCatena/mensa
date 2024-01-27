import { FormControl } from "@angular/forms";

export const fullDateValidator = (control: FormControl) => {
    const value = control.value;

    if (value === null || value === undefined || value === '') {
        return null; // No validation error if the control is empty
    }

    const dateTimeLocalRegex = /^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/;

    return dateTimeLocalRegex.test(value) ? null : { invalidDateTimeLocalFormat: true };
}