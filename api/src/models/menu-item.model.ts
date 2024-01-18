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

@Table({
	tableName: 'menu_items'
})
export class MenuItem extends Model<MenuItem> {
	@PrimaryKey
	@AutoIncrement
	@Column({ type: DataType.INTEGER.UNSIGNED, allowNull: false })
	id: number;

	@ForeignKey(() => Mensa)
	@Column({
		type: DataType.INTEGER.UNSIGNED,
		allowNull: false,
		field: 'mensa_id'
	})
	mensaId: number;

	@BelongsTo(() => Mensa, 'mensa_id')
	mensa: Mensa;

	@Column({ type: DataType.SMALLINT, allowNull: false })
	order: number;

	@Column({ type: DataType.STRING(191), allowNull: false })
	text: string;
}
