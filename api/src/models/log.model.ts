import { Table, Column, Model, DataType, ForeignKey, BelongsTo, PrimaryKey } from 'sequelize-typescript';
import { Mensa } from './mensa.model';
import { User } from './user.model';

@Table({
  tableName: 'logs',
})
export class Log extends Model<Log> {

    @PrimaryKey
    @Column({type: DataType.INTEGER.UNSIGNED, autoIncrement: true, allowNull: false})
    id: number;

    @Column({type: DataType.STRING(191), allowNull: false})
    description: string;

    @ForeignKey(() => Mensa)
    @Column({
    type: DataType.INTEGER.UNSIGNED,
    allowNull: false,
    })
    mensa_id: number;

    @ForeignKey(() => User)
    @Column({
    type: DataType.STRING(191),
    field: 'lidnummer',
    })
    userId?: number;

    @BelongsTo(() => Mensa)
    mensa: Mensa;

    @BelongsTo(() => User)
    user: User;
}
