import { MenuItem } from './menu-item.model';
import { MensaExtraOption } from './mensa-extra-option';
export class Mensa {
	id: number;
	title: string;
	date: Date;
	closingTime: Date;
	maxUsers: number;
    enrollments: number;
	price: number;
	closed: boolean;
    menuItems: MenuItem[];
    extraOptions: MensaExtraOption[];
	createdAt: Date;
	updatedAt: Date;

	constructor(
		id: number,
		title: string,
		date: Date,
		closingTime: Date,
		maxUsers: number,
        enrollments: number,
		price: number,
		closed: boolean,
        menuItems: MenuItem[],
        extraOptions: MensaExtraOption[],
		createdAt: Date,
		updatedAt: Date
	) {
		this.id = id;
		this.title = title;
		this.date = date;
		this.closingTime = closingTime;
		this.maxUsers = maxUsers;
        this.enrollments = enrollments;
		this.price = price;
		this.closed = closed;
        this.menuItems = menuItems;
        this.extraOptions = extraOptions;
		this.createdAt = createdAt;
		this.updatedAt = updatedAt;
	}
}
