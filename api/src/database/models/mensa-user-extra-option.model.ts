import {
	Table,
	Model,
	ForeignKey,
	BelongsTo,
	DataType,
	Column
} from 'sequelize-typescript';
import { MensaUser } from './mensa-user.model';
import { MensaExtraOption } from './mensa-extra-option.model';

@Table({
	tableName: 'mensa_user_extra_options',
	timestamps: false,
	underscored: true
})
export class MensaUserExtraOption extends Model<MensaUserExtraOption> {
	@Column({ type: DataType.INTEGER.UNSIGNED, allowNull: false })
	mensaUserId: number;

	@Column({ type: DataType.INTEGER.UNSIGNED, allowNull: false })
	mensaExtraOptionId: number;

	@ForeignKey(() => MensaUser)
	@BelongsTo(() => MensaUser, 'mensa_user_id')
	mensaUser: MensaUser;

	@ForeignKey(() => MensaExtraOption)
	@BelongsTo(() => MensaExtraOption, 'mensa_extra_option_id')
	mensaExtraOption: MensaExtraOption;
}
