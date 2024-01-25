import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class LoginService {

  constructor() { }

  isLoggedIn(): boolean {
    // TODO implement is logged in method
    return true;
  }

  logout(): void {
    // TODO implement logout function
    // TODO redirect to home?
    // Clear local storage
  }
}
