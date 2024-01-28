import {
	Model,
	Column,
	PrimaryKey,
	AutoIncrement,
	ForeignKey,
	BelongsTo,
	DataType,
	Table
} from 'sequelize-typescript';
import { Mensa } from './mensa.model';
import { Type } from 'class-transformer';
import { IsString, Matches, MaxLength, MinLength } from 'class-validator';

@Table
export class MensaExtraOption extends Model<MensaExtraOption> {
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

	@IsString()
	@MinLength(3, {
		message: 'The description must be at least 3 characters long'
	})
	@MaxLength(191, {
		message: 'The description can be at most 191 characters long'
	})
	@Column({ type: DataType.STRING(191), allowNull: false })
	description: string;

	@Type(() => String)
	@Matches(/^\d+(\.\d{1,2})?$/)
	@Column({ type: DataType.DECIMAL(8, 2), allowNull: false })
	price: number;
}
