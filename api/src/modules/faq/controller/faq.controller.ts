import { Controller, Get } from '@nestjs/common';
import { FaqService } from '../service/faq.service';
import { Faq } from 'src/database/models/faq.model';
import { ApiResponse } from '@nestjs/swagger';

@Controller('faq')
export class FaqController {

    constructor(private readonly faqService: FaqService) { }

    @Get()
    @ApiResponse({ status: 200, type: Faq, isArray: true })
    async findAll(): Promise<Faq[]> {
        return this.faqService.findAll();
    }

}
