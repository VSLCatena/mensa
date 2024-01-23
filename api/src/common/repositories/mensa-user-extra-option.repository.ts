import { Injectable } from '@nestjs/common';
import { InjectModel } from '@nestjs/sequelize';
import { MensaUserExtraOption } from 'src/database/models/mensa-user-extra-option.model';
import { BaseRepository } from './base.repository';

@Injectable()
export class MensaUserExtraOptionRepository extends BaseRepository<MensaUserExtraOption> {
	constructor(
		@InjectModel(MensaUserExtraOption)
		readonly model: typeof MensaUserExtraOption
	) {
		super(model);
	}
}
