import { AuthService } from 'src/app/common/services/auth/auth.service';
import { Component } from '@angular/core';

@Component({
	selector: 'app-navbar',
	templateUrl: './navbar.component.html',
	styleUrl: './navbar.component.scss'
})
export class NavbarComponent {
	public isLoggedIn: boolean = this.authService.isLoggedIn();
	public isAdmin: boolean = this.authService.isAdminUser();
	public isServiceUser: boolean = this.authService.isServiceUser();
	public username: string = 'fgdfg ';

	constructor(private readonly authService : AuthService) {}
}
