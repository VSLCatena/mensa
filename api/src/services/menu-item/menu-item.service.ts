import { Injectable } from '@nestjs/common';
import { InjectModel } from '@nestjs/sequelize';
import { MenuItem } from 'src/database/models/menu-item.model';

@Injectable()
export class MenuItemService {

    constructor(
        @InjectModel(MenuItem)
        private readonly menuItemModel: typeof MenuItem
    ) {}

    findAll(mensaId: number): Promise<MenuItem[]> {
        return this.menuItemModel.findAll({
            where: {
                mensaId: mensaId
            }
        });
    }
}

