import { Controller, Get, Query } from '@nestjs/common';
import { MensaService } from 'src/services/mensa-service/mensa.service';
import { Mensa } from 'src/database/models/mensa.model';
import { ApiQuery } from '@nestjs/swagger';

@Controller('mensa')
export class MensaController {

    constructor(
        private readonly mensaService: MensaService,
    ) {}

    /**
     * @summary Gets mensae for given week + 1
     * @description Gets mensae for given week + 1, where the weeks starts on Monday and ends on Sunday
     * @param {number} page - The page number, can be negative (e.g. -1 for last week)
     * @returns {Mensa[]} Successful response
     */
    @Get()
    @ApiQuery({ name: 'page', type: Number, required: true })
    async findAll(@Query('page') page: number): Promise<Mensa[]> {
        return await this.mensaService.findAll(page);
    }
}
