import { Injectable } from '@nestjs/common';
import { FaqRepository } from 'src/common/repositories/faq.repository';
import { Faq } from 'src/database/models/faq.model';

@Injectable()
export class FaqService {

    constructor(private readonly faqRepository: FaqRepository) {}

    async findAll(): Promise<Faq[]> {
        return this.faqRepository.findAll();
    }
}
