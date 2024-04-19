import { Module } from '@nestjs/common';
import { MensaUserController } from './controller/mensa-user.controller';
import { MensaUserService } from './service/mensa-user.service';
import { CommonModule } from 'src/common/common.module';

@Module({
  controllers: [MensaUserController],
  providers: [MensaUserService],
  imports: [CommonModule]
})
export class MensaUserModule {}
