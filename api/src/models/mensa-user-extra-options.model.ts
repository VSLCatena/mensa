import { Table, Model, Column, ForeignKey, BelongsTo, DataType } from 'sequelize-typescript';
import { MensaUser } from './mensa-user.model';
import { MensaExtraOption } from './mensa-extra-option.model';

@Table({
  tableName: 'mensa_user_extra_options',
  timestamps: false, // Disable timestamps for this table
})
export class MensaUserExtraOptions extends Model<MensaUserExtraOptions> {
  @ForeignKey(() => MensaUser)
  @Column({ type: DataType.INTEGER, allowNull: false })
  mensa_user_id: number;

  @ForeignKey(() => MensaExtraOption)
  @Column({ type: DataType.INTEGER, allowNull: false })
  mensa_extra_option_id: number;

  @BelongsTo(() => MensaUser)
  mensaUser: MensaUser;

  @BelongsTo(() => MensaExtraOption)
  mensaExtraOption: MensaExtraOption;
}
