import { ApiQuery } from '@nestjs/swagger';
import { MenuItemService } from './../../services/menu-item/menu-item.service';
import { Controller, Get, Query } from '@nestjs/common';
import { MenuItem } from 'src/database/models/menu-item.model';

@Controller('menu-item')
export class MenuItemController {
    constructor(private readonly menuItemService: MenuItemService) {}

    /**
     * @summary Gets menu items for given mensa
     * @param {number} mensaId - The mensa id
     * @returns {MenuItem[]} Successful response
     */
    @Get()
    @ApiQuery({ name: 'mensaId', type: Number, required: true })
    async findAll(@Query('mensaId') mensaId: number): Promise<MenuItem[]> {
        return await this.menuItemService.findAll(mensaId);
    }
}
