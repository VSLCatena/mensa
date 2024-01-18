import { Sequelize } from 'sequelize-typescript';
import { Faq } from 'src/database/models/faq.model';
import { MensaExtraOption } from 'src/database/models/mensa-extra-option.model';
import { MensaUserExtraOption } from 'src/database/models/mensa-user-extra-option.model';
import { MensaUser } from 'src/database/models/mensa-user.model';
import { Mensa } from 'src/database/models/mensa.model';
import { MenuItem } from 'src/database/models/menu-item.model';
import { User } from 'src/database/models/user.model';

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
				pool: {
					max: 5,
					min: 0,
					acquire: 30000,
					idle: 10000
				}
			});
			sequelize.addModels([
				User,
				Mensa,
				Faq,
				MenuItem,
				MensaUser,
				MensaExtraOption,
				MensaUserExtraOption
			]);
			await sequelize.sync();
			return sequelize;
		}
	}
];
