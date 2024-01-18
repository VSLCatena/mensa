import { Module } from '@nestjs/common';
import { databaseProviders } from './common/providers/database.provider';
import { ConfigModule } from '@nestjs/config';
import { MensaController } from './controllers/mensa/mensa.controller';
import { MensaService } from './services/mensa-service/mensa.service';
import { MenuItemService } from './services/menu-item/menu-item.service';
import { MensaUserService } from './services/mensa-user/mensa-user.service';
import { MensaExtraOptionService } from './services/mensa-extra-option/mensa-extra-option.service';
import { UserService } from './services/user/user.service';

@Module({
	imports: [ConfigModule.forRoot()],
	providers: [...databaseProviders, MensaService, MenuItemService, MensaUserService, MensaExtraOptionService, UserService],
	controllers: [MensaController]
})
export class AppModule {}
