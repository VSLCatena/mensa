import { SequelizeModule } from '@nestjs/sequelize';
import { Module } from '@nestjs/common';
import { ConfigModule } from '@nestjs/config';
import { MensaModule } from './modules/mensa/mensa.module';
import { CommonModule } from './common/common.module';
import { models } from './database/models.database';

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
				underscored: true
			}
		}),
		SequelizeModule.forFeature(models),
		MensaModule,
		CommonModule
	]
})
export class AppModule {}
