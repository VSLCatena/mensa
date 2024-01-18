export class Mensa {
	id: number;
	title: string;
	date: Date;
	closingTime: Date;
	maxUsers: number;
    enrollments: number;
	price: number;
	closed: boolean;
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
		this.createdAt = createdAt;
		this.updatedAt = updatedAt;
	}
}
