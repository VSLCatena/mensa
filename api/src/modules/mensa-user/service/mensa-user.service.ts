import { Injectable, NotFoundException } from '@nestjs/common';
import { MensaUserDto } from '../dto/mensa-user.dto';
import { MensaUserRepository } from 'src/common/repositories/mensa-user.repository';
import { MensaRepository } from 'src/common/repositories/mensa.repository';
import { User } from 'src/database/models/user.model';

@Injectable()
export class MensaUserService {

	constructor(
		private readonly mensaRepository: MensaRepository,
		private readonly mensaUserRepository: MensaUserRepository
	) {}

    async registerUserToMensa(mensaUserDto: MensaUserDto, user: User) {
        try {
            this.mensaRepository.findById(mensaUserDto.mensaId); // Check if mensa exists, throws error if not
            this.createMensaUsers(mensaUserDto, user);
        } catch (ex) {
            throw new NotFoundException(`Non-existing mensa for given mensa id: ${mensaUserDto.mensaId}`);
        }
    }

    private createMensaUsers(mensaUserDto: MensaUserDto, user: User): void {
        const mensaUser = mensaUserDto.getMensaUser(user.membershipNumber);
        this.mensaUserRepository.create(mensaUser);

        if (mensaUserDto.intro != null) {
            const introMensaUser = mensaUserDto.getIntroMensaUser(user.membershipNumber);
            this.mensaUserRepository.create(introMensaUser);
        }
    }
}
