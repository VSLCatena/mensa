import { InjectModel } from '@nestjs/sequelize';
import { Injectable } from '@nestjs/common';
import { BaseRepository } from './base.repository';
import { MensaUser } from 'src/database/models/mensa-user.model';

@Injectable()
export class MensaUserRepository extends BaseRepository<MensaUser> {
	constructor(
		@InjectModel(MensaUser)
		readonly model: typeof MensaUser
	) {
		super(model);
	}
}
