import { Component } from '@angular/core';

@Component({
  selector: 'app-navbar',
  templateUrl: './navbar.component.html',
  styleUrl: './navbar.component.scss'
})
export class NavbarComponent {
  public isLoggedIn: boolean = false;
  public isAdmin: boolean = false;
  public isServiceUser: boolean = false;
  public username: string = 'fgdfg ';

}
