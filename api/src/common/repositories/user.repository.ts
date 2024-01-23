import { Injectable } from '@nestjs/common';
import { BaseRepository } from './base.repository';
import { User } from 'src/database/models/user.model';
import { InjectModel } from '@nestjs/sequelize';

@Injectable()
export class UserRepository extends BaseRepository<User> {
	constructor(
		@InjectModel(User)
		readonly model: typeof User
	) {
		super(model);
	}
}
