import { SequelizeModule } from '@nestjs/sequelize';
import { MiddlewareConsumer, Module, NestModule } from '@nestjs/common';
import { ConfigModule } from '@nestjs/config';
import { MensaModule } from './modules/mensa/mensa.module';
import { CommonModule } from './common/common.module';
import { models } from './database/models.database';
import { FaqModule } from './modules/faq/faq.module';
import { MensaUserModule } from './modules/mensa-user/mensa-user.module';
import { UserMiddleware } from './middlewares/user.middleware';

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
			},
			logging: false
		}),
		SequelizeModule.forFeature(models),
		MensaModule,
		CommonModule,
		FaqModule,
		MensaUserModule
	]
})
export class AppModule implements NestModule {
	configure(consumer: MiddlewareConsumer) {
		consumer.apply(UserMiddleware).forRoutes('*'); // Change this to Controllers.
	}
}
