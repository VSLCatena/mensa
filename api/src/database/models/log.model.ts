import {
	Table,
	Model,
	Column,
	ForeignKey,
	BelongsTo,
	CreatedAt,
	UpdatedAt,
	PrimaryKey,
	DataType
} from 'sequelize-typescript';
import { User } from './user.model';
import { Mensa } from './mensa.model';

@Table
export class Log extends Model<Log> {
	@PrimaryKey
	@Column({
		type: DataType.INTEGER.UNSIGNED,
		autoIncrement: true,
		allowNull: false
	})
	id: number;

	@ForeignKey(() => Mensa)
	@Column({
		type: DataType.INTEGER.UNSIGNED,
		allowNull: false,
	})
	mensaId: number;

	@ForeignKey(() => User)
	@Column({ type: DataType.STRING(191) })
	membershipNumber?: string;

	@Column({ type: DataType.STRING(191), allowNull: false })
	description: string;

	@CreatedAt
	createdAt: Date;

	@UpdatedAt
	updatedAt: Date;

	@BelongsTo(() => Mensa)
	mensa: Mensa;

	@BelongsTo(() => User)
	user: User;
}
