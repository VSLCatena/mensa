import { SequelizeModule } from '@nestjs/sequelize';
import { Module } from '@nestjs/common';
import { ConfigModule } from '@nestjs/config';
import { MensaController } from './controllers/mensa/mensa.controller';
import { MensaService } from './services/mensa-service/mensa.service';
import { MenuItemService } from './services/menu-item/menu-item.service';
import { MensaUserService } from './services/mensa-user/mensa-user.service';
import { MensaExtraOptionService } from './services/mensa-extra-option/mensa-extra-option.service';
import { UserService } from './services/user/user.service';
import { Mensa } from './database/models/mensa.model';
import { Faq } from './database/models/faq.model';
import { Log } from './database/models/log.model';
import { MensaExtraOption } from './database/models/mensa-extra-option.model';
import { MensaUserExtraOption } from './database/models/mensa-user-extra-option.model';
import { MensaUser } from './database/models/mensa-user.model';
import { MenuItem } from './database/models/menu-item.model';
import { User } from './database/models/user.model';

var models = [Faq, Log, MensaExtraOption, MensaUserExtraOption, MensaUser, Mensa, MenuItem, User];

@Module({
	imports: [
		ConfigModule.forRoot(),
		SequelizeModule.forRoot({
			dialect: 'mariadb',
			host: process.env.DB_HOST,
			port: parseInt(process.env.DB_PORT),
			username: process.env.DB_USERNAME,
			password: process.env.DB_PASSWORD,
			database: process.env.DB_DATABASE,
			models: models,
			retryAttempts: 10,
			retryDelay: 2000,
			autoLoadModels: true,
			synchronize: Boolean(process.env.DEV_ENVIRONMENT).valueOf(), // Only synchronise when in dev environment
			define: {
				underscored: true,
			}
  		}),
		SequelizeModule.forFeature(models)
	],
	providers: [MensaService, MenuItemService, MensaUserService, MensaExtraOptionService, UserService],
	controllers: [MensaController]
})
export class AppModule {}
