import { Module } from '@nestjs/common';
import { FaqController } from './controller/faq.controller';
import { FaqService } from './service/faq.service';
import { CommonModule } from 'src/common/common.module';

@Module({
  controllers: [FaqController],
  providers: [FaqService],
  imports: [CommonModule]
})
export class FaqModule {}
