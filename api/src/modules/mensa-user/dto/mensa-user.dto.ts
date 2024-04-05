import { IsBoolean, IsOptional, IsString } from 'class-validator';
import { IntroDto } from './intro.dto';
import { Type } from 'class-transformer';

export class MensaUserDto {

    @IsString()
	mensaId: string;

    @IsBoolean()
    isVegetarian: boolean;

    @IsString()
    allergies: string;

    @IsString()
    extraInfo: string;

    @IsOptional()
    @Type(() => IntroDto)
	intro: IntroDto | null;

    @IsBoolean()
    volunteerDishwasher: boolean;

    @IsBoolean()
    volunteerCook: boolean;
}
