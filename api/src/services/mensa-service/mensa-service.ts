import { Injectable } from '@nestjs/common';
import { Mensa } from 'src/database/models/mensa.model';

@Injectable()
export class MensaService {
    private readonly mensa: Mensa[] = [];

    findAll(): Mensa[] {
        return this.mensa;
    }
}
