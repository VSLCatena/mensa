import {
	IsString,
	IsDate,
	IsInt,
	IsArray,
	ValidateNested,
	Matches
} from 'class-validator';
import { Type } from 'class-transformer';
import { MensaExtraOption } from 'src/database/models/mensa-extra-option.model';
import { MenuItem } from 'src/database/models/menu-item.model';
import { Mensa } from 'src/database/models/mensa.model';

export class CreateMensaDto {
	@IsString()
	title: string;

	@Type(() => Date)
	@IsDate()
	date: Date;

	@Type(() => Date)
	@IsDate()
	closingTime: Date;

	@IsInt()
	maxUsers: number;

	@Type(() => String)
	@IsString()
	@Matches(/^\d+(\.\d{1,2})?$/)
	price: number;

	@IsArray()
	@ValidateNested({ each: true })
	@Type(() => MenuItem)
	menuItems: MenuItem[];

	@IsArray()
	@ValidateNested({ each: true })
	@Type(() => MensaExtraOption)
	extraOptions: MensaExtraOption[];

	public getMensa(): Mensa {
		var mensa = new Mensa();
		mensa.title = this.title;
		mensa.date = this.date;
		mensa.closingTime = this.closingTime;
		mensa.maxUsers = this.maxUsers;
		mensa.price = this.price;
		return mensa;
	}
}
