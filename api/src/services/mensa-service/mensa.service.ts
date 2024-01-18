import { Injectable } from '@nestjs/common';
import { InjectModel } from '@nestjs/sequelize';
import { Mensa } from 'src/database/models/mensa.model';

@Injectable()
export class MensaService {

    constructor(
        @InjectModel(Mensa)
        private readonly mensaModel: typeof Mensa
    ) {}

    findAll(): Promise<Mensa[]> {
        return this.mensaModel.findAll();
    }
}
