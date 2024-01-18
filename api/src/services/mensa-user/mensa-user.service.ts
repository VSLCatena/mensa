import { Injectable } from '@nestjs/common';
import { InjectModel } from '@nestjs/sequelize';
import { MensaUser } from 'src/database/models/mensa-user.model';

@Injectable()
export class MensaUserService {
    constructor(
        @InjectModel(MensaUser)
        private readonly mensaUserModel: typeof MensaUser
    ) {}

    findAll(mensaId: number): Promise<MensaUser[]> {
        return this.mensaUserModel.findAll({
            where: {
                mensaId: mensaId
            }
        });
    }
}
