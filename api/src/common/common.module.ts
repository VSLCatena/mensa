import { Module } from '@nestjs/common';
import { MensaProvider } from './providers/mensa-provider/mensa-provider';

@Module({
  providers: [MensaProvider],
  exports: [MensaProvider]
})
export class CommonModule {}
