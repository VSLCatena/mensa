import { Injectable } from '@nestjs/common';
import { MensaProvider } from 'src/common/providers/mensa-provider/mensa-provider';

@Injectable()
export class MensaService {

    constructor(private readonly mensaProvider: MensaProvider) {}

    async findAll(page: number) {
        return await this.mensaProvider.findAll(page);
    }
}
