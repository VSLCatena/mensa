import { Injectable } from '@nestjs/common';
import { Op } from 'sequelize';
import { getWeekDate } from 'src/common/helpers/date.helper';
import { MensaRepository } from 'src/common/repositories/mensa.repository';
import { DayOfWeek } from 'src/common/types/day-of-week.type';
import { MensaDto } from '../dto/mensa.dto';
import { Mensa } from 'src/database/models/mensa.model';
import { MenuItemRepository } from 'src/common/repositories/menu-item.repository';
import { MensaExtraOptionRepository } from 'src/common/repositories/mensa-extra-option.repository';
import { MensaUserRepository } from 'src/common/repositories/mensa-user.repository';
import { MensaUser } from 'src/database/models/mensa-user.model';

@Injectable()
export class MensaService {

    constructor(private readonly mensaRepository: MensaRepository, private readonly menuItemRepository: MenuItemRepository,
        private readonly mensaExtraOptionRepository: MensaExtraOptionRepository,
        private readonly mensaUserRepository: MensaUserRepository) {}

    async findAll(page: number): Promise<MensaDto[]> {
        const currentWeek = getWeekDate(DayOfWeek.Monday, page);
        const nextWeek = getWeekDate(DayOfWeek.Monday, page + 1);

        const mensae = await this.mensaRepository.findAll({
            where: {
                date: {
                    [Op.between]: [currentWeek, nextWeek]
                }
            }
        });

        return await this.createMensaDto(mensae);

    }

    private async createMensaDto(mensae: Mensa[]) {
        const mensaDtoList: MensaDto[] = [];
        for (const mensa of mensae) {
            const mensaDto = new MensaDto();
            const mensaUsers = await this.mensaUserRepository.findAll({ where: { mensaId: mensa.id } });
            mensaDto.mensa = mensa;
            mensaDto.menuItems = await this.menuItemRepository.findAll({ where: { mensaId: mensa.id } });
            mensaDto.extraOptions = await this.mensaExtraOptionRepository.findAll({ where: { mensaId: mensa.id } });
            await this.addStaff(mensaDto, mensaUsers);
            mensaDto.enrollments = mensaUsers.length;
            mensaDtoList.push(mensaDto);
        }
        return mensaDtoList;
    }

    private async addStaff(mensaDto: MensaDto, mensaUsers: MensaUser[]) {
        mensaDto.cooks = mensaUsers
        .filter((mensaUser) => mensaUser.cooks)
        .map((mensaUser) => mensaUser.user.name);

        mensaDto.dishwashers = mensaUsers
        .filter((mensaUser) => mensaUser.dishwasher)
        .map((mensaUser) => mensaUser.user.name);
    }
}
