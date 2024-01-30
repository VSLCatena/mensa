import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { Mensa } from 'src/app/common/models/mensa.model';
import { MenuItem } from 'src/app/common/models/menu-item.model';
import { MensaService } from 'src/app/common/services/mensa/mensa.service';
import { MensaDto } from 'src/app/common/models/dto/mensa.dto';
import { TableNavigate } from 'src/app/common/types/table-navigate.type';
import { DayOfWeek } from 'src/app/common/types/day-of-week.type';
import { AuthService } from 'src/app/common/services/auth/auth.service';

@Component({
	selector: 'app-home-screen',
	templateUrl: './home-screen.component.html',
	styleUrl: './home-screen.component.scss'
})
export class HomeScreenComponent implements OnInit {
	private calledPages: number[] = [0];
	public page: number = 0;
	public mensaeDtoList: { [page: string]: MensaDto[] } = { '0': [] };

	public tableNavigate = TableNavigate;
	public dayOfWeek = DayOfWeek;
	isLoading: boolean = true;

	constructor(
		private readonly mensaService: MensaService,
		private readonly authService: AuthService
	) {}

	ngOnInit(): void {
		this.mensaService.getMensae().subscribe(mensaeDto => {
			this.mensaeDtoList[this.page.toString()] = mensaeDto;
			this.isLoading = false;
		});
	}

	public getDateForPage(page: number, day: DayOfWeek): Date {
		const today = new Date();
		const distance = day - today.getDay() + 7 * page;
		today.setDate(today.getDate() + distance);
		today.setHours(0, 0, 0, 0);
		return today;
	}

	public getMenuItems(menuItems: MenuItem[]): MenuItem[] {
		return menuItems.sort((a, b) => a.order - b.order);
	}

	public isLoggedIn(): boolean {
		return this.authService.isLoggedIn();
	}

	public isServiceUser(): boolean {
		return this.authService.isServiceUser();
	}

	public getPage(navigate: TableNavigate): void {
		const page =
			navigate === TableNavigate.Previous ? this.page - 1 : this.page + 1;

		if (!this.calledPages.includes(page)) {
			this.calledPages.push(page);
			this.mensaService.getMensae(page).subscribe(mensaeDto => {
				this.page = page;
				this.mensaeDtoList[this.page.toString()] = mensaeDto;
			});
		} else {
			this.page = page;
		}
	}
}