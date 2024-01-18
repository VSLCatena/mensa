import { Controller, Get } from '@nestjs/common';
import { MensaService } from 'src/services/mensa-service/mensa.service';
import { MensaDto } from './dto/mensa.dto';
import { MenuItemService } from 'src/services/menu-item/menu-item.service';
import { MensaUserService } from 'src/services/mensa-user/mensa-user.service';
import { Mensa } from 'src/database/models/mensa.model';
import { MensaExtraOptionService } from 'src/services/mensa-extra-option/mensa-extra-option.service';
import { MensaUser } from 'src/database/models/mensa-user.model';
import { StaffDto } from './dto/staff.dto';
import { UserService } from 'src/services/user/user.service';
import { StaffRole } from 'src/common/types/staff-role.type';
import { MenuItemDto } from './dto/menu-item.dto';
import { MenuItem } from 'src/database/models/menu-item.model';
import { MensaExtraOption } from 'src/database/models/mensa-extra-option.model';
import { MensaExtraOptionDto } from './dto/mensa-extra-option.dto';

@Controller('mensa')
export class MensaController {

    constructor(
        private readonly mensaService: MensaService,
        private readonly menuItemService: MenuItemService,
        private readonly mensaUserService: MensaUserService,
        private readonly mensaExtraOptionService: MensaExtraOptionService,
        private readonly userService: UserService
        ) {}

    @Get()
    async findAll(): Promise<MensaDto[]> {
        // TODO pagination
        let mensae = await this.mensaService.findAll();

        const mensaeDtoPromises = mensae.map(async mensa => await this.createMensaDto(mensa));
        const mensaeDto = await Promise.all(mensaeDtoPromises);

        return mensaeDto;
    }

    private async createMensaDto(mensa: Mensa): Promise<MensaDto> {
        let mensaUsers = await this.mensaUserService.findAllByMensaId(mensa.id);
        let menuItems = await this.menuItemService.findAllByMensaId(mensa.id);
        let menuExtraOption = await this.mensaExtraOptionService.findAllByMensaId(mensa.id);

        let mensaDto = new MensaDto();
        mensaDto.staff = await this.getStaff(mensaUsers);
        mensaDto.menuItems = this.getMenuItemsDto(menuItems);
        mensaDto.extraOptions = this.getMensaExtraOptionDto(menuExtraOption);


        mensaDto.id = mensa.id;
        mensaDto.title = mensa.title;


        return mensaDto;
    }

    private async getStaff(mensaUsers: MensaUser[]): Promise<StaffDto[]> {
        return Promise.all(mensaUsers
            .filter(mensaUser => mensaUser.cooks || mensaUser.dishwasher)
            .map(async mensaUser => {
                const staff = new StaffDto();
                const user = await this.userService.findByMembershipNumber(mensaUser.membershipNumber);
                staff.name = user.name;
                staff.role = mensaUser.cooks ? StaffRole.Cook : StaffRole.Dishwasher;
                return staff;
            }));
    }

    private getMenuItemsDto(menuItems: MenuItem[]): MenuItemDto[] {
        return menuItems.map(menuItem => ({
            id: menuItem.id,
            mensaId: menuItem.mensaId,
            description: menuItem.text,
            order: menuItem.order,
        }));
    }

    private getMensaExtraOptionDto(mensaExtraOptions: MensaExtraOption[]): MensaExtraOptionDto[] {
        return mensaExtraOptions.map(mensaExtraOption => ({
            id: mensaExtraOption.id,
            mensaId: mensaExtraOption.mensaId,
            description: mensaExtraOption.description,
            price: mensaExtraOption.price,
        }));
    }
}
