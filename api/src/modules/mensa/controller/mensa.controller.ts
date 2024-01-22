import { Controller, Get, Query } from '@nestjs/common';
import { ApiQuery } from '@nestjs/swagger';
import { Mensa } from 'src/database/models/mensa.model';
import { MensaService } from '../service/mensa.service';

@Controller('mensa')
export class MensaController {
    constructor(
        private readonly mensaService: MensaService,
    ) { }

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
