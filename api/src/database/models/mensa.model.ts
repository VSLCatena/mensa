import {
	Table,
	Model,
	Column,
	CreatedAt,
	UpdatedAt,
	PrimaryKey,
	DataType
} from 'sequelize-typescript';

@Table({
	tableName: 'mensas'
})
export class Mensa extends Model<Mensa> {
	@PrimaryKey
	@Column({
		type: DataType.INTEGER.UNSIGNED,
		autoIncrement: true,
		allowNull: false
	})
	id: number;

	@Column({ type: DataType.STRING(191), allowNull: false })
	title: string;

	@Column({ type: DataType.DATE, allowNull: false })
	date: Date;

	@Column({ type: DataType.DATE, allowNull: false, field: 'closing_time' })
	closingTime: Date;

	@Column({ type: DataType.TINYINT, allowNull: false, field: 'max_users' })
	maxUsers: number;

	@Column({ type: DataType.DECIMAL(8, 2), allowNull: false })
	price: number;

	@Column({ type: DataType.BOOLEAN, allowNull: false, defaultValue: false })
	closed: boolean;

	@CreatedAt
	createdAt: Date;

	@UpdatedAt
	updatedAt: Date;
}