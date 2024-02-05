import { Module } from '@nestjs/common';
import { MensaUserController } from './controller/mensa-user.controller';
import { MensaUserService } from './service/mensa-user.service';

@Module({
  controllers: [MensaUserController],
  providers: [MensaUserService]
})
export class MensaUserModule {}
