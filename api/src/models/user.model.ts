import { Table, Model, Column, PrimaryKey, CreatedAt, UpdatedAt, Default, AllowNull } from 'sequelize-typescript';

@Table({
  tableName: 'users',
})
export class User extends Model<User> {
  @PrimaryKey
  @Column
  lidnummer: string;

  @Column
  name: string;

  @Column
  email: string;

  @AllowNull
  @Column
  allergies?: string;

  @AllowNull
  @Column
  wishes?: string;

  @Default(false)
  @Column
  mensa_admin: boolean;

  @Column
  rememberToken: string;

  @CreatedAt
  createdAt: Date;

  @UpdatedAt
  updatedAt: Date;
}
