import { Injectable } from '@nestjs/common';
import { InjectModel } from '@nestjs/sequelize';
import { MensaExtraOption } from 'src/database/models/mensa-extra-option.model';

@Injectable()
export class MensaExtraOptionService {
    constructor(
        @InjectModel(MensaExtraOption)
        private readonly mensaExtraOptionModel: typeof MensaExtraOption
    ) {}

    findAllByMensaId(mensaId: number): Promise<MensaExtraOption[]> {
        return this.mensaExtraOptionModel.findAll({
            where: {
                mensaId: mensaId
            }
        });
    }
}
