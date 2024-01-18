import { Table, Model, Column, ForeignKey, BelongsTo, CreatedAt, UpdatedAt } from 'sequelize-typescript';
import { User } from './user.model';

@Table({
  tableName: 'faqs',
})
export class FAQ extends Model<FAQ> {
  @Column({ autoIncrement: true, primaryKey: true })
  id: number;

  @Column
  question: string;

  @Column({ type: 'LONGTEXT' })
  answer: string;

  @ForeignKey(() => User)
  @Column
  last_edited_by: string;

  @CreatedAt
  createdAt: Date;

  @UpdatedAt
  updatedAt: Date;

  @BelongsTo(() => User)
  lastEditedBy: User;
}
