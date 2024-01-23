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

@Table
export class Faq extends Model<Faq> {
	@PrimaryKey
	@Column({
		type: DataType.INTEGER.UNSIGNED,
		autoIncrement: true,
		allowNull: false
	})
	id: number;

	@Column({ type: DataType.STRING(191), allowNull: false })
	question: string;

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
