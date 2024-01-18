import { StaffRole } from './../../common/types/staff-role.type';
import { Component } from '@angular/core';
import { Router } from '@angular/router';
import { Mensa } from 'src/app/common/models/mensa.model';
import { MenuItem } from 'src/app/common/models/menu-item.model';

@Component({
	selector: 'app-home-screen',
	templateUrl: './home-screen.component.html',
	styleUrl: './home-screen.component.scss'
})
export class HomeScreenComponent {
	public page: number = 0;
	public mensae: Mensa[] = [];
  public staffRole = StaffRole;

  constructor(private readonly router: Router) { }

	public getDateForPage(page: number): string {
		const today = new Date();
		today.setDate(today.getDate() + page);
		const options: Intl.DateTimeFormatOptions = {
			day: '2-digit',
			month: '2-digit',
			year: 'numeric'
		};
		return today.toLocaleDateString('en-GB', options);
	}

  public getMenuItems(mensa: Mensa): MenuItem[] {
    return mensa.menuItems.sort((a, b) => a.order - b.order);
  }

  public getStaff(mensa: Mensa, role: StaffRole): string {
    return mensa.staff.filter((staff) => staff.role == role).map((staff) => staff.name).join(' en ');
  }

  public getStaffAmount(mensa: Mensa, role: StaffRole): number {
    return mensa.staff.filter((staff) => staff.role == role).length;
  }

  public isLoggedIn(): boolean {
    // TODO Check if logged in
    return false;
  }

  public isServiceUser(): boolean {
    // TODO Check if service user
    return false;
  }

  public signoutFormSubmit(mensa: Mensa): void {
    this.router.navigate(['/signout', mensa.id])
  }

  public shouldShowCancelledBlock(mensa: Mensa): boolean {
    return mensa.maxUsers <= 0 && (!this.isLoggedIn() || !this.isServiceUser());
  }

  public shouldShowSignOutForm(mensa: Mensa): boolean {
    // TODO Wanneer ingelogd en ingeschreven
    return false;

  }

  public shouldShowSignUpBlock(mensa: Mensa): boolean {
    return mensa.maxUsers > mensa.enrollments;
  }
}
