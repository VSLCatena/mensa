import { MensaExtraOption } from "../mensa-extra-option.model";
import { MenuItem } from "../menu-item.model";

export class CreateMensaDto {
    title: string;
    date: string;
    closingTime: string;
    maxUsers: string;
    price: string;
    menu: MenuItem[];
    extraOptions: MensaExtraOption[];

    constructor(
        title: string,
        date: string,
        closingTime: string,
        maxUsers: string,
        price: string,
        menu: MenuItem[],
        extraOptions: MensaExtraOption[]
    ) {
        this.title = title;
        this.date = date;
        this.closingTime = closingTime;
        this.maxUsers = maxUsers;
        this.price = price;
        this.menu = menu;
        this.extraOptions = extraOptions;
    }
}