import { TestBed } from '@angular/core/testing';
import { MensaUserService } from './mensa-user.service';

describe('MensaUserService', () => {
	let service: MensaUserService;

	beforeEach(() => {
		TestBed.configureTestingModule({});
		service = TestBed.inject(MensaUserService);
	});

	it('should be created', () => {
		expect(service).toBeTruthy();
	});
});
