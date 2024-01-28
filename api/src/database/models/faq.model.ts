import {
	Table,
	Model,
	Column,
	CreatedAt,
	UpdatedAt,
	PrimaryKey,
	DataType,
	ForeignKey,
	BelongsTo
} from 'sequelize-typescript';
import { User } from './user.model';
import { MinLength, MaxLength, IsString } from 'class-validator';

@Table
export class Faq extends Model<Faq> {
	@PrimaryKey
	@Column({
		type: DataType.INTEGER.UNSIGNED,
		autoIncrement: true,
		allowNull: false
	})
	id: number;

	@IsString()
	@MinLength(3, { message: 'The question must be at least 3 characters long' })
	@MaxLength(191, { message: 'The question can be at most 191 characters long' })
	@Column({ type: DataType.STRING(191), allowNull: false })
	question: string;

	@IsString()
	@MinLength(3, { message: 'The answer must be at least 3 characters long' })
	@Column({ type: DataType.TEXT, allowNull: false })
	answer: string;

	@ForeignKey(() => User)
	@Column({
		type: DataType.STRING(191),
		allowNull: false
	})
	lastEditedBy: string;

	@BelongsTo(() => User)
	lastEditedByUser: User;

	@CreatedAt
	createdAt: Date;

	@UpdatedAt
	updatedAt: Date;
}
