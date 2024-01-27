import { Component } from '@angular/core';
import { Error } from 'src/app/common/models/error.model';
import { FormGroup, FormControl, Validators, FormBuilder, FormArray, Form } from '@angular/forms';
import { MensaService } from 'src/app/common/services/mensa/mensa.service';
import { fullDateValidator, integerValidator } from 'src/app/common/helpers/custom.validators';
import { CreateMensaDto } from 'src/app/common/models/dto/create-mensa.dto';
import { BsDatepickerConfig } from 'ngx-bootstrap/datepicker';
import { formatDateTime } from 'src/app/common/helpers/date.formatter';

@Component({
	selector: 'app-mensa-creation-screen',
	templateUrl: './mensa-creation-screen.component.html',
	styleUrl: './mensa-creation-screen.component.scss'
})
export class MensaCreationScreenComponent {
	public error: Error = new Error();
	public isSuccess: boolean = false;
	public isLoading = false;
	public mensaForm: FormGroup = new FormGroup({});

	public datepickerConfig: Partial<BsDatepickerConfig>;

	get menuControls() {
		return (this.mensaForm.get('menu') as FormArray).controls;
	}

	get extraOptionsControls() {
		return (this.mensaForm.get('extraOptions') as FormArray).controls;
	}

	constructor(private formBuilder: FormBuilder, private mensaService: MensaService) {
		this.datepickerConfig = Object.assign({}, {
			containerClass: 'theme-dark-blue',
			keepDatepickerOpened: true,
			rangeInputFormat: 'DD-MM-YYYY HH:mm',
			isAnimated: true,
			dateInputFormat: 'DD-MM-YYYY HH:mm',
			withTimepicker: true,
			minuteStep: 15,

		});
		this.setCleanForm();
		this.mensaForm.get('date')!.valueChanges.subscribe((value) => {
			this.mensaForm.get('closingTime')!.setValue(this.setDateAndTime(value, 15, 0));
		});
	}

	public setCleanForm(): void {
		this.mensaForm = this.formBuilder.group({
			title: new FormControl('', [
				Validators.required,
				Validators.minLength(3),
				Validators.maxLength(190),
				Validators.pattern(/^(m\||p\|)(.*)$/)
			]),
			date: new FormControl(this.setDateAndTime(new Date(), 18, 30), [Validators.required, fullDateValidator]),
			closingTime: new FormControl(this.setDateAndTime(new Date(), 15, 0), [Validators.required, fullDateValidator]),
			maxUsers: new FormControl('', [Validators.required, integerValidator]),
			price: new FormControl(4, [Validators.required, Validators.pattern(/^\d+(\.\d{1,2})?$/)]),
			menu: this.formBuilder.array([]),
			extraOptions: this.formBuilder.array([]),
		});
	}

	public onSubmit(): void {
		this.isSuccess = false;
		this.error = new Error();
		this.isLoading = true;
		if (!this.mensaForm.valid) {
			this.isLoading = false;
			return this.error.setError(
				true,
				'Niet alle velden zijn goed ingevuld'
			);
		}

		var mensa = new CreateMensaDto();
		mensa.mapForm(this.mensaForm);


		this.mensaService.createMensa(mensa).subscribe({
			next: result => {
				this.isLoading = false;
				this.isSuccess = true;
				this.setCleanForm();
			},
			error: err => {
				console.log(err)
				this.error.setError(true, err.error.message.message);
				this.isLoading = false;
			}
		});
	}

	public isValid(formController: string, index: number = -1): boolean {
		if (index >= 0) {
			var formArray = this.mensaForm.get(formController) as FormArray;
			const item = formArray.at(index) as FormGroup;
			return item.valid;
		}

		return this.mensaForm.controls[formController].valid;
	}

	public addMenuItem(): void {
		const menuItem = this.formBuilder.group({
			order: [(this.menuControls.length + 1), Validators.required],
			text: ['', Validators.required],
		});

		(this.mensaForm.get('menu') as FormArray).push(menuItem);
	}

	public addExtraOption(): void {
		const extraOption = this.formBuilder.group({
			description: ['', [Validators.required, Validators.minLength(3)]],
			price: [0, [Validators.required, Validators.pattern(/^\d+(\.\d{1,2})?$/)]],
		});

		(this.mensaForm.get('extraOptions') as FormArray).push(extraOption);
	}

	public deleteOption(index: number, formName: string): void {
		(this.mensaForm.get(formName) as FormArray).removeAt(index);
	}

	private setDateAndTime(orignalDate: Date, hours: number, minutes: number): Date {
		var newDate = new Date(orignalDate);
		newDate.setHours(hours);
		newDate.setMinutes(minutes);
		return newDate;
	}
}
