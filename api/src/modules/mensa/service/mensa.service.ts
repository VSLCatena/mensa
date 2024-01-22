import { Injectable } from '@nestjs/common';
import { MensaRepository } from 'src/common/repositories/mensa-repository/mensa-repository';

@Injectable()
export class MensaService {

    constructor(private readonly mensaRepository: MensaRepository) {}

    async findAll(page: number) {
        return await this.mensaRepository.findAll(page);
    }
}
