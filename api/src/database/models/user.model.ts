import {
	Table,
	Model,
	Column,
	CreatedAt,
	UpdatedAt,
	PrimaryKey,
	DataType
} from 'sequelize-typescript';

@Table
export class User extends Model<User> {
	@PrimaryKey
	@Column({
		type: DataType.STRING(191),
		allowNull: false
	})
	membershipNumber: string;

	@Column({ type: DataType.STRING(191), allowNull: false })
	name: string;

	@Column({ type: DataType.STRING(191) })
	email?: string;

	@Column({ type: DataType.STRING(191) })
	allergies?: string;

	@Column({ type: DataType.STRING(191) })
	extraInfo?: string;

	@Column({ type: DataType.BOOLEAN, allowNull: false, defaultValue: false })
	mensaAdmin: boolean;

	@Column({ type: DataType.STRING(100) })
	rememberToken?: string;

	@Column({ type: DataType.STRING(191) })
	phoneNumber?: string;

	@Column({ type: DataType.BOOLEAN, allowNull: false, defaultValue: false })
	vegetarian: boolean;

	@Column({ type: DataType.BOOLEAN, allowNull: false, defaultValue: false })
	serviceUser: boolean;

	@CreatedAt
	createdAt: Date;

	@UpdatedAt
	updatedAt: Date;
}
