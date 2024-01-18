import { SequelizeModule } from '@nestjs/sequelize';
import { Module } from '@nestjs/common';
import { ConfigModule } from '@nestjs/config';
import { MensaController } from './controllers/mensa/mensa.controller';
import { MensaService } from './services/mensa-service/mensa.service';
import { MenuItemService } from './services/menu-item/menu-item.service';
import { MensaUserService } from './services/mensa-user/mensa-user.service';
import { MensaExtraOptionService } from './services/mensa-extra-option/mensa-extra-option.service';
import { UserService } from './services/user/user.service';

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
			autoLoadModels: true,
			synchronize: true
  		})
	],
	providers: [MensaService, MenuItemService, MensaUserService, MensaExtraOptionService, UserService],
	controllers: [MensaController]
})
export class AppModule {}
