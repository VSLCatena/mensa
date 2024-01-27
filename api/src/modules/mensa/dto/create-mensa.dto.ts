import { MensaExtraOption } from "src/database/models/mensa-extra-option.model";
import { MenuItem } from "src/database/models/menu-item.model";

export class CreateMensaDto {
    title: string;
    date: string;
    closingTime: string;
    maxUsers: string;
    price: string;
    menu: MenuItem[];
    extraOptions: MensaExtraOption[];
}

