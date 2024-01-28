import { Injectable } from '@nestjs/common';
import { BaseRepository } from './base.repository';
import { InjectModel } from '@nestjs/sequelize';
import { Faq } from 'src/database/models/faq.model';

@Injectable()
export class FaqRepository extends BaseRepository<Faq> {
	constructor(
		@InjectModel(Faq)
		readonly model: typeof Faq
	) {
		super(model);
	}
}
