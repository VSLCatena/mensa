import { Injectable } from '@nestjs/common';
import { BaseRepository } from './base.repository';
import { MenuItem } from 'src/database/models/menu-item.model';
import { InjectModel } from '@nestjs/sequelize';

@Injectable()
export class MenuItemRepository extends BaseRepository<MenuItem> {
    constructor(
        @InjectModel(MenuItem)
        readonly model: typeof MenuItem
    ) {
        super(model);
     }
}
