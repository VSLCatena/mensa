import { Sequelize } from 'sequelize-typescript';
import { FAQ } from 'src/models/faq.model';
import { MensaExtraOption } from 'src/models/mensa-extra-option.model';
import { MensaUserExtraOptions } from 'src/models/mensa-user-extra-options.model';
import { MensaUser } from 'src/models/mensa-user.model';
import { Mensa } from 'src/models/mensa.model';
import { MenuItem } from 'src/models/menu-item.model';
import { User } from 'src/models/user.model';

export const databaseProviders = [
  {
    provide: 'SEQUELIZE',
    useFactory: async () => {
      const sequelize = new Sequelize({
        dialect: 'mariadb',
        host: process.env.DB_HOST,
        port: parseInt(process.env.DB_PORT),
        username: process.env.DB_USERNAME,
        password: process.env.DB_PASSWORD,
        database: process.env.DB_DATABASE,
      });
      sequelize.addModels([FAQ, MensaExtraOption, Mensa, MensaUser, MensaUserExtraOptions, MenuItem, User]);
      await sequelize.sync();
      return sequelize;
    },
  },
];
