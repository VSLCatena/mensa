import { Module } from '@nestjs/common';
import { MensaController } from './controller/mensa.controller';
import { MensaService } from './service/mensa.service';
import { CommonModule } from 'src/common/common.module';

@Module({
  controllers: [MensaController],
  providers: [MensaService],
  imports: [CommonModule]
})
export class MensaModule {}
