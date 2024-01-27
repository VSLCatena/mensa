import { AbstractControl, ValidationErrors, ValidatorFn } from '@angular/forms';

export function fullDateValidator(): ValidatorFn {
  return (control: AbstractControl): ValidationErrors | null => {
    const value: string = control.value as string;

    if (!value) {
      return null;
    }

    const dateTimeLocalRegex = /^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/;

    return dateTimeLocalRegex.test(value) ? null : { invalidDateTimeLocalFormat: true };
  };
}

export function integerValidator(): ValidatorFn {
  return (control: AbstractControl): ValidationErrors | null => {
    const value: number = control.value as number;

    if (isNaN(value) || Number.isInteger(value)) {
      return null;
    } else {
      return { notAnInteger: true };
    }
  };
}
