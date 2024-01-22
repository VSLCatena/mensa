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
	tableName: 'mensa_extra_options'
})
export class MensaExtraOption extends Model<MensaExtraOption> {
	@PrimaryKey
	@AutoIncrement
	@Column({ type: DataType.INTEGER.UNSIGNED, allowNull: false })
	id: number;

	@ForeignKey(() => Mensa)
	@Column({
		type: DataType.INTEGER.UNSIGNED,
		allowNull: false,
	})
	mensaId: number;

	@BelongsTo(() => Mensa)
	mensa: Mensa;

	@Column({ type: DataType.STRING(191), allowNull: false })
	description: string;

	@Column({ type: DataType.DECIMAL(8, 2), allowNull: false })
	price: number;
}
