import { Component, Input, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { Mensa } from 'src/app/common/models/mensa.model';
import { AuthService } from 'src/app/common/services/auth/auth.service';

@Component({
	selector: 'app-mensa-button',
	templateUrl: './mensa-button.component.html',
	styleUrl: './mensa-button.component.scss'
})
export class MensaButtonComponent implements OnInit {
	@Input({ required: true }) mensa!: Mensa;
	@Input({ required: true }) enrollments: number = 0;

	buttonState:
		| 'Inschrijven'
		| 'Uitschrijven'
		| 'Gesloten'
		| 'Geannuleerd'
		| 'Vol' = 'Inschrijven';
	isDisabled: boolean = true;

	constructor(
		private authService: AuthService,
		private router: Router
	) {}

	ngOnInit(): void {
		if (this.mensa.maxUsers <= 0) {
			this.buttonState = 'Geannuleerd';
		} else if (this.mensa.closed) {
			this.buttonState = 'Gesloten';
		} else if (this.authService.isLoggedIn()) {
			this.setUserEnrolledButtonState();
		} else if (this.isMensaFull()) {
			this.buttonState = 'Vol';
		} else {
			this.buttonState = 'Inschrijven';
			this.isDisabled = false;
		}
	}

	setUserEnrolledButtonState(): void {
		if (false) {
			// TODO is Ennrolled check if user is enrolled in mensa
			this.buttonState = 'Uitschrijven';
			return;
		}

		if (!this.isMensaFull()) {
			this.buttonState = 'Inschrijven';
			this.isDisabled = false;
		} else {
			this.buttonState = 'Vol';
		}
	}

	isMensaFull(): boolean {
		return this.mensa.maxUsers === this.enrollments;
	}

	onButtonClick(): void {
		if (this.buttonState === 'Inschrijven') {
			this.router.navigate(['register', this.mensa.id]);
		} else if (this.buttonState === 'Uitschrijven') {
			// TODO Uitschrijven mensa en een alert?
		}
	}
}
