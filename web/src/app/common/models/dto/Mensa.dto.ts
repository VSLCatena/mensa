import { MensaExtraOption } from "../mensa-extra-option.model";
import { MenuItem } from "../menu-item.model";

export class Mensa {
	mensa: Mensa;
	cooks: string[];
	dishwashers: string[];
	menuItems: MenuItem[];
	extraOptions: MensaExtraOption[];
	enrollments: number;

	constructor(
        mensa: Mensa,
        cooks: string[],
        dishwashers: string[],
        menuItems: MenuItem[],
        extraOptions: MensaExtraOption[],
        enrollments: number,
	) {
        this.mensa = mensa;
        this.cooks = cooks;
        this.dishwashers = dishwashers;
        this.menuItems = menuItems;
        this.extraOptions = extraOptions;
        this.enrollments = enrollments;
	}
}
