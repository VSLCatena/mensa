// mensa-user.model.ts
import { Table, Model, Column, PrimaryKey, ForeignKey, BelongsTo, CreatedAt, UpdatedAt, DataType } from 'sequelize-typescript';
import { User } from './user.model';
import { Mensa } from './mensa.model';

@Table({
  tableName: 'mensa_users',
})
export class MensaUser extends Model<MensaUser> {
  @PrimaryKey
  @Column({ autoIncrement: true })
  id: number;

  @Column
  lidnummer: string;

  @ForeignKey(() => User)
  @Column
  mensa_id: number;

  @Column(DataType.BOOLEAN)
  cooks: boolean;

  @Column(DataType.BOOLEAN)
  dishwasher: boolean;

  @Column(DataType.BOOLEAN)
  is_intro: boolean;

  @Column
  allergies?: string;

  @Column
  wishes?: string;

  @Column({ defaultValue: false })
  confirmed: boolean;

  @Column({ defaultValue: false })
  paid: boolean;

  @BelongsTo(() => User, 'lidnummer')
  user: User;

  @BelongsTo(() => Mensa)
  mensa: Mensa;

  @CreatedAt
  createdAt: Date;

  @UpdatedAt
  updatedAt: Date;
}
