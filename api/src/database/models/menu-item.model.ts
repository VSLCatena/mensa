import {
	Table,
	Model,
	Column,
	PrimaryKey,
	AutoIncrement,
	ForeignKey,
	BelongsTo,
	DataType
} from 'sequelize-typescript';
import { Mensa } from './mensa.model';
import { Min, Max, IsInt } from 'class-validator';

@Table
export class MenuItem extends Model<MenuItem> {
	@PrimaryKey
	@AutoIncrement
	@Column({ type: DataType.INTEGER.UNSIGNED, allowNull: false })
	id: number;

	@ForeignKey(() => Mensa)
	@Column({
		type: DataType.INTEGER.UNSIGNED,
		allowNull: false
	})
	mensaId: number;

	@BelongsTo(() => Mensa)
	mensa: Mensa;

	@IsInt()
	@Min(0)
	@Max(10)
	@Column({ type: DataType.SMALLINT, allowNull: false })
	order: number;

	@Column({ type: DataType.STRING(191), allowNull: false })
	text: string;
}
