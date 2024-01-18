import { Module } from '@nestjs/common';
import { databaseProviders } from './common/providers/database.provider';
import { ConfigModule } from '@nestjs/config';
import { MensaController } from './controllers/mensa/mensa.controller';
import { MensaService } from './services/mensa-service/mensa-service';

@Module({
	imports: [ConfigModule.forRoot()],
	providers: [...databaseProviders, MensaService],
	controllers: [MensaController]
})
export class AppModule {}
