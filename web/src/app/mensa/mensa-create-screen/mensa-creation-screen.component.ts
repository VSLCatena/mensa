import { Component } from '@angular/core';
import { FormGroup, FormControl, Validators, FormBuilder, FormArray } from '@angular/forms';

@Component({
	selector: 'app-mensa-creation-screen',
	templateUrl: './mensa-creation-screen.component.html',
	styleUrl: './mensa-creation-screen.component.scss'
})
export class MensaCreationScreenComponent {
	public error: Error = new Error();
	public isLoading = false;
	public mensaForm: FormGroup;

	// Convenience getter for easy access to form controls
	get menuControls() {
		return (this.mensaForm.get('menu') as FormArray).controls;
	}

	get extraOptionsControls() {
		return (this.mensaForm.get('extraOptions') as FormArray).controls;
	}

	constructor(private formBuilder: FormBuilder) {
		this.mensaForm = this.formBuilder.group({
			titel: new FormControl('', [Validators.required]),
			label: new FormControl('', [Validators.required]),
			date: new FormControl('', [Validators.required]),
			closingTime: new FormControl('', [Validators.required]),
			maxUsers: new FormControl('', [Validators.required]),
			price: new FormControl(4, [Validators.required]),
			menu: formBuilder.array([]),
			extraOptions: formBuilder.array([]),
		});

	}

	public onSubmit(): void {
		// TODO submit form
		console.log("SUBMIT")
	}

	public addMenuItem(): void {
		const menuItem = this.formBuilder.group({
			order: [(this.menuControls.length + 1), Validators.required],
			text: ['', Validators.required],
		});

		// Add menu item to form
		(this.mensaForm.get('menu') as FormArray).push(menuItem);
	}

	public addExtraOption(): void {
		const extraOption = this.formBuilder.group({
			description: ['', Validators.required],
			price: [0, Validators.required],
		});

		// Add extra option to form
		(this.mensaForm.get('extraOptions') as FormArray).push(extraOption);
	}

	public deleteOption(index: number, formName: string): void {
		(this.mensaForm.get(formName) as FormArray).removeAt(index);
	}
}
