import { ComponentFixture, TestBed } from '@angular/core/testing';

import { MensaButtonComponent } from './mensa-button.component';

describe('MensaButtonComponent', () => {
	let component: MensaButtonComponent;
	let fixture: ComponentFixture<MensaButtonComponent>;

	beforeEach(async () => {
		await TestBed.configureTestingModule({
			declarations: [MensaButtonComponent]
		}).compileComponents();

		fixture = TestBed.createComponent(MensaButtonComponent);
		component = fixture.componentInstance;
		fixture.detectChanges();
	});

	it('should create', () => {
		expect(component).toBeTruthy();
	});
});
