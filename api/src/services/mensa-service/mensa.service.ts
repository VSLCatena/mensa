import { Injectable } from '@nestjs/common';
import { InjectModel } from '@nestjs/sequelize';
import { Op } from 'sequelize';
import { getWeekDate } from 'src/common/helpers/date.helper';
import { DayOfWeek } from 'src/common/types/day-of-week.type';
import { Mensa } from 'src/database/models/mensa.model';

@Injectable()
export class MensaService {

    constructor(
        @InjectModel(Mensa)
        private readonly mensaModel: typeof Mensa
    ) {}

    findAll(page: number): Promise<Mensa[]> {
        const currentWeek = getWeekDate(DayOfWeek.Monday, page);
        const nextWeek = getWeekDate(DayOfWeek.Sunday, page);

        return this.mensaModel.findAll({
            where: {
                date: {
                    [Op.between]: [currentWeek, nextWeek]
                }
            }
        });
    }
}
