import { Injectable } from '@nestjs/common';
import { Mensa } from 'src/database/models/mensa.model';
import { MensaRepository } from 'src/common/repositories/mensa.repository';
import { MenuItemRepository } from 'src/common/repositories/menu-item.repository';
import { MensaExtraOptionRepository } from 'src/common/repositories/mensa-extra-option.repository';
import { MensaUserRepository } from 'src/common/repositories/mensa-user.repository';
import { Op } from 'sequelize';
import { getWeekDate } from 'src/common/helpers/date.helper';
import { DayOfWeek } from 'src/common/types/day-of-week.type';
import { MensaUser } from 'src/database/models/mensa-user.model';
import { MensaDto } from '../dto/mensa.dto';
import { User } from 'src/database/models/user.model';
import { CreateMensaDto } from '../dto/create-mensa.dto';

@Injectable()
export class MensaService {
	constructor(
		private readonly mensaRepository: MensaRepository,
		private readonly menuItemRepository: MenuItemRepository,
		private readonly mensaExtraOptionRepository: MensaExtraOptionRepository,
		private readonly mensaUserRepository: MensaUserRepository
	) {}

	async findAll(page: number): Promise<MensaDto[]> {
		page = page < 0 ? page - 1 : page;
		const currentWeek = getWeekDate(DayOfWeek.Monday, page);
		let nextWeekPage = page === 0 ? page + 2 : page + 1;
		const nextWeek = getWeekDate(DayOfWeek.Monday, nextWeekPage);

		const mensae = await this.mensaRepository.findAll({
			where: {
				date: {
					[Op.between]: [currentWeek, nextWeek]
				}
			},
			order: [['date', 'ASC']]
		});

		return await this.createMensaDto(mensae);
	}

	private async createMensaDto(mensae: Mensa[]) {
		const mensaDtoList: MensaDto[] = [];
		for (const mensa of mensae) {
			const mensaDto = new MensaDto();
			mensaDto.mensa = mensa;
			mensaDto.menuItems = await this.menuItemRepository.findAll({
				where: { mensaId: mensa.id }
			});
			mensaDto.extraOptions =
				await this.mensaExtraOptionRepository.findAll({
					where: { mensaId: mensa.id }
				});

			const mensaUsers = await this.mensaUserRepository.findAll({
				where: { mensaId: mensa.id },
				include: [User]
			});
			await this.addStaff(mensaDto, mensaUsers);
			mensaDto.enrollments = mensaUsers.length;
			mensaDtoList.push(mensaDto);
		}
		return mensaDtoList;
	}

	private async addStaff(mensaDto: MensaDto, mensaUsers: MensaUser[]) {
		mensaDto.cooks = mensaUsers
			.filter(mensaUser => mensaUser.cooks)
			.map(mensaUser => mensaUser.user.name);

		mensaDto.dishwashers = mensaUsers
			.filter(mensaUser => mensaUser.dishwasher)
			.map(mensaUser => mensaUser.user.name);
	}

	async saveMensaDto(createMensaDto: CreateMensaDto): Promise<void> {
		var mensa = await this.mensaRepository.create(createMensaDto.getMensa());
		createMensaDto.menuItems.map(menuItem => {
			menuItem.mensaId = mensa.id;
			this.menuItemRepository.create(menuItem);
		});

		createMensaDto.extraOptions.map(extraOption => {
			extraOption.mensaId = mensa.id;
			this.mensaExtraOptionRepository.create(extraOption);
		});
	}
}
