import { Injectable } from '@nestjs/common';
import { InjectModel } from '@nestjs/sequelize';
import { Mensa } from 'src/database/models/mensa.model';
import { BaseRepository } from './base.repository';

@Injectable()
export class MensaRepository extends BaseRepository<Mensa> {
	constructor(
		@InjectModel(Mensa)
		readonly model: typeof Mensa
	) {
		super(model);
	}
}
