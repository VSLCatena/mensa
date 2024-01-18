import { Table, Model, Column, PrimaryKey, CreatedAt, UpdatedAt } from 'sequelize-typescript';

@Table({
  tableName: 'mensas',
})
export class Mensa extends Model<Mensa> {
  @PrimaryKey
  @Column({ autoIncrement: true })
  id: number;

  @Column
  title: string;

  @Column({ type: 'TIMESTAMP' })
  date: Date;

  @Column({ type: 'TIMESTAMP' })
  closing_time: Date;

  @Column
  max_users: number;

  @CreatedAt
  createdAt: Date;

  @UpdatedAt
  updatedAt: Date;
}
