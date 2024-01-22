import { Module } from '@nestjs/common';
import { MensaExtraOptionRepository } from './repositories/mensa-extra-option-repository/mensa-extra-option-repository';
import { MensaUserExtraOptionRepository } from './repositories/mensa-user-extra-option-repository/mensa-user-extra-option-repository';
import { MensaUserRepository } from './repositories/mensa-user-repository/mensa-user-repository';
import { MenuItemRepository } from './repositories/menu-item-repository/menu-item-repository';
import { UserRepository } from './repositories/user-repository/user-repository';
import { MensaRepository } from './repositories/mensa-repository/mensa-repository';

@Module({
    providers: [MensaRepository, MensaExtraOptionRepository, MensaUserExtraOptionRepository, MensaUserRepository, MenuItemRepository, UserRepository],
    exports: [MensaRepository, MensaExtraOptionRepository, MensaUserExtraOptionRepository, MensaUserRepository, MenuItemRepository, UserRepository],
    imports: []
})
export class CommonModule { }
