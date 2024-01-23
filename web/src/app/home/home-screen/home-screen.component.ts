import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { Mensa } from 'src/app/common/models/mensa.model';
import { MenuItem } from 'src/app/common/models/menu-item.model';
import { MensaService } from 'src/app/common/services/mensa/mensa.service';
import { MensaDto } from '../../../../../api/src/modules/mensa/dto/mensa.dto';

@Component({
	selector: 'app-home-screen',
	templateUrl: './home-screen.component.html',
	styleUrl: './home-screen.component.scss'
})
export class HomeScreenComponent implements OnInit {
	public page: number = 0;
	public mensaeDto: MensaDto[] = [];

  constructor(private readonly router: Router, private readonly mensaService: MensaService) { }

  ngOnInit(): void {
    this.mensaService.getMensae().subscribe(mensaeDto => {
      this.mensaeDto = mensaeDto;
    });
  }

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

  public getMenuItems(menuItems: MenuItem[]): MenuItem[] {
    return menuItems.sort((a, b) => a.order - b.order);
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

  public shouldShowSignUpBlock(mensaDto: MensaDto): boolean {
    return mensaDto.mensa.maxUsers > mensaDto.enrollments;
  }
}
