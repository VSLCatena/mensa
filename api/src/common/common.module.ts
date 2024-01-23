import { Module } from '@nestjs/common';
import { UserRepository } from './repositories/user.repository';
import { MensaRepository } from './repositories/mensa.repository';
import { MenuItemRepository } from './repositories/menu-item.repository';
import { MensaUserRepository } from './repositories/mensa-user.repository';
import { MensaUserExtraOptionRepository } from './repositories/mensa-user-extra-option.repository';
import { MensaExtraOptionRepository } from './repositories/mensa-extra-option.repository';
import { SequelizeModule } from '@nestjs/sequelize';
import { models } from 'src/database/models.database';

const repositories = [MensaExtraOptionRepository, MensaUserExtraOptionRepository, MensaUserRepository, MensaRepository, MenuItemRepository, UserRepository]

@Module({
    providers: [
        ...repositories,
    ],
    exports: [
        ...repositories,
    ],
  imports: [SequelizeModule.forFeature(models)]
})
export class CommonModule { }