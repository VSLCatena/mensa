import {
	Table,
	Model,
	Column,
	PrimaryKey,
	AutoIncrement,
	ForeignKey,
	BelongsTo,
	DataType,
	CreatedAt,
	UpdatedAt,
	DeletedAt
} from 'sequelize-typescript';
import { User } from './user.model';
import { Mensa } from './mensa.model';

@Table({
	paranoid: true
})
export class MensaUser extends Model<MensaUser> {
	@PrimaryKey
	@AutoIncrement
	@Column({ type: DataType.INTEGER.UNSIGNED, allowNull: false })
	id: number;

	@ForeignKey(() => User)
	@Column({
		type: DataType.STRING(191),
		allowNull: false
	})
	membershipNumber: string;

	@BelongsTo(() => User)
	user: User;

	@ForeignKey(() => Mensa)
	@Column({
		type: DataType.INTEGER.UNSIGNED,
		allowNull: false
	})
	mensaId: number;

	@BelongsTo(() => Mensa)
	mensa: Mensa;

	@Column({ type: DataType.BOOLEAN, allowNull: false, defaultValue: false })
	cooks: boolean;

	@Column({ type: DataType.BOOLEAN, allowNull: false, defaultValue: false })
	dishwasher: boolean;

	@Column({
		type: DataType.BOOLEAN,
		allowNull: false,
		defaultValue: false
	})
	isIntro: boolean;

	@Column({ type: DataType.STRING(191), defaultValue: null })
	allergies?: string;

	@Column({
		type: DataType.STRING(191),
		defaultValue: null
	})
	extraInfo?: string;

	@Column({ type: DataType.BOOLEAN, allowNull: false, defaultValue: false })
	confirmed: boolean;

	@Column({
		type: DataType.DECIMAL(8, 2),
		allowNull: false,
		defaultValue: 0.0
	})
	paid: number;

	@CreatedAt
	createdAt: Date;

	@UpdatedAt
	updatedAt: Date;

	@DeletedAt
	deletedAt: Date;

	@Column({
		type: DataType.STRING(191),
		allowNull: false
	})
	confirmationCode: string;

	@Column({ type: DataType.BOOLEAN, allowNull: false, defaultValue: false })
	vegetarian: boolean;
}
