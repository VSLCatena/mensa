import { Controller, Get, ParseIntPipe, Query, Post, Body, UsePipes, ValidationPipe, UseFilters } from '@nestjs/common';
import { ApiBody, ApiQuery, ApiOperation, ApiResponse } from '@nestjs/swagger';
import { MensaService } from '../service/mensa.service';
import { MensaDto } from '../dto/mensa.dto';
import { CreateMensaDto } from '../dto/create-mensa.dto';

@Controller('mensa')
export class MensaController {
	constructor(private readonly mensaService: MensaService) {}

	@Get()
	@ApiOperation({ summary: 'Gets mensae for given week + 1' })
	@ApiQuery({ name: 'page', type: Number, required: true })
	@ApiResponse({ status: 200, type: MensaDto, isArray: true })
	async findAll(@Query('page', ParseIntPipe) page: number): Promise<MensaDto[]> {
		return await this.mensaService.findAll(page);
	}

	@Post()
	@UsePipes(new ValidationPipe({transform: true}))
	@ApiOperation({ summary: 'Creates a new mensa' })
	@ApiBody({ type: CreateMensaDto })
	@ApiResponse({ status: 201, description: 'Mensa created successfully!' })
	async create(@Body() createMensa: CreateMensaDto): Promise<{ message: string }> {
		await this.mensaService.saveMensaDto(createMensa);
		return { message: 'Data received successfully!' };
	}
}
