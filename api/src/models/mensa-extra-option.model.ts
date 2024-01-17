// mensa-extra-option.model.ts
import { Table, Model, Column, ForeignKey, BelongsTo } from 'sequelize-typescript';
import { Mensa } from './mensa.model';

@Table({
  tableName: 'mensa_extra_options',
})
export class MensaExtraOption extends Model<MensaExtraOption> {
  @Column({ autoIncrement: true, primaryKey: true })
  id: number;

  @ForeignKey(() => Mensa)
  @Column
  mensa_id: number;

  @Column
  description: string;

  @Column
  price: number;

  @BelongsTo(() => Mensa)
  mensa: Mensa;
}
