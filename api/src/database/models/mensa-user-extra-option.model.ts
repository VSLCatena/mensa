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
  timestamps: false,
})
export class MensaUserExtraOption extends Model<MensaUserExtraOption> {
  @ForeignKey(() => MensaUser)
  @Column({ type: DataType.INTEGER.UNSIGNED, allowNull: false})
  mensaUserId: number;

  @ForeignKey(() => MensaExtraOption)
  @Column({ type: DataType.INTEGER.UNSIGNED, allowNull: false })
  mensaExtraOptionId: number;

  @BelongsTo(() => MensaUser)
  mensaUser: MensaUser;

  @BelongsTo(() => MensaExtraOption)
  mensaExtraOption: MensaExtraOption;
}
