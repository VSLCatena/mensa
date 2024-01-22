import { Injectable } from '@nestjs/common';
import { BaseRepository } from './base.repository';
import { MensaExtraOption } from 'src/database/models/mensa-extra-option.model';
import { InjectModel } from '@nestjs/sequelize';

@Injectable()
export class MensaExtraOptionRepository extends BaseRepository<MensaExtraOption> {
    constructor(
        @InjectModel(MensaExtraOption)
        readonly model: typeof MensaExtraOption
    ) {
        super(model);
     }
}
