import { Module } from '@nestjs/common';
import { MensaController } from './controller/mensa.controller';
import { MensaService } from './service/mensa.service';
@Module({
  controllers: [MensaController],
  providers: [MensaService]
})
export class MensaModule {}
