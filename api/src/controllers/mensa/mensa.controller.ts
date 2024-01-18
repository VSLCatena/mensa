import { Controller, Get } from '@nestjs/common';
import { Mensa } from 'src/database/models/mensa.model';
import { MensaService } from 'src/services/mensa-service/mensa-service';

@Controller('mensa')
export class MensaController {

    constructor(private readonly mensaService: MensaService) {}

    @Get()
    async findAll(): Promise<Mensa[]> {
        return this.mensaService.findAll();
    }

}
