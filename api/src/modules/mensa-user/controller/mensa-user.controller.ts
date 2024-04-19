import { Body, Controller, Post, UsePipes, ValidationPipe } from '@nestjs/common';
import { ApiOperation, ApiBody, ApiResponse } from '@nestjs/swagger';
import { MensaUserDto } from '../dto/mensa-user.dto';
import { MensaUserService } from '../service/mensa-user.service';
import { User } from 'src/database/models/user.model';
import { UserDecorator } from 'src/common/decorators/user.decorator';

@Controller('mensa-user')
export class MensaUserController {

    constructor(private mensaUserService: MensaUserService) {

    }

    @Post()
	@UsePipes(new ValidationPipe({ transform: true }))
	@ApiOperation({ summary: 'Adds an user to the mensa by creating a mensa user that is connected to the mensa' })
	@ApiBody({ type: MensaUserDto })
	@ApiResponse({ status: 201, description: 'User added to mensa succesfully!' })
	async create(
		@Body() mensaUser: MensaUserDto,
		@UserDecorator() user: User
	): Promise<{ message: string }> {
		await this.mensaUserService.registerUserToMensa(mensaUser, user);
		return { message: 'Data received successfully!' };
	}
}
