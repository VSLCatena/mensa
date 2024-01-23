import { Controller, Get, ParseIntPipe, Query } from '@nestjs/common';
import { ApiQuery } from '@nestjs/swagger';
import { MensaService } from '../service/mensa.service';
import { MensaDto } from '../dto/mensa.dto';

@Controller('mensa')
export class MensaController {
	constructor(private readonly mensaService: MensaService) {}

	/**
	 * @summary Gets mensae for given week + 1
	 * @description Gets mensae for given week + 1, where the weeks starts on Monday and ends on Sunday
	 * @param {number} page - The page number, can be negative (e.g. -1 for last week)
	 * @returns {Mensa[]} Successful response
	 */
	@Get()
	@ApiQuery({ name: 'page', type: Number, required: true })
	async findAll(@Query('page', ParseIntPipe) page: number): Promise<MensaDto[]> {
		return await this.mensaService.findAll(page);
	}
}
