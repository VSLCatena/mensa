import { MensaExtraOption } from "src/database/models/mensa-extra-option.model";
import { Mensa } from "src/database/models/mensa.model";
import { MenuItem } from "src/database/models/menu-item.model";

export class MensaDto {
    mensa: Mensa;
    cooks: string[];
    dishwashers: string[];
    menuItems: MenuItem[];
    extraOptions: MensaExtraOption[];
    enrollments: number;
}