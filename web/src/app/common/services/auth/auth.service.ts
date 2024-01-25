import { Injectable } from '@angular/core';
import { User } from '../../models/user.model';

@Injectable({
	providedIn: 'root'
})
export class AuthService {
	constructor() {}

	login(username: string, password: string): void {
		// TODO implement login function
		// TODO redirect to home?
		// Store token in local storage
	}

	isLoggedIn(): boolean {
		// TODO implement is logged in method
		return true;
	}

	logout(): void {
		// TODO implement logout function
		// TODO redirect to home?
		// Clear local storage
	}

	isServiceUser(): boolean {
		// TODO Implement is service user method
		return false;
	}

	isAdminUser(): boolean {
		// TODO Implement is admin user method
		return false;
	}

	getUser(): User {
		// TODO Implement get user method
		return new User('Test', 'User', false, false, false);
	}
}
