import { Table, Model, Column, ForeignKey, BelongsTo } from 'sequelize-typescript';
import { Mensa } from './mensa.model';

@Table({
  tableName: 'menu_items',
})
export class MenuItem extends Model<MenuItem> {
  @Column({ autoIncrement: true, primaryKey: true })
  id: number;

  @ForeignKey(() => Mensa)
  @Column
  mensa_id: number;

  @Column
  order: number;

  @Column
  text: string;

  @BelongsTo(() => Mensa)
  mensa: Mensa;
}
