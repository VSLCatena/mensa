import { Module } from '@nestjs/common';
import { databaseProviders } from './common/providers/database.provider';
import { ConfigModule } from '@nestjs/config';
import { MensaController } from './controllers/mensa/mensa.controller';
import { MensaService } from './services/mensa-service/mensa.service';
import { MenuItemService } from './services/menu-item/menu-item.service';
import { MensaUserService } from './services/mensa-user/mensa-user.service';

@Module({
	imports: [ConfigModule.forRoot()],
	providers: [...databaseProviders, MensaService, MenuItemService, MensaUserService],
	controllers: [MensaController]
})
export class AppModule {}
