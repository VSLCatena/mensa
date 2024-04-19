import { IsBoolean, IsInt, IsOptional, IsString } from 'class-validator';
import { IntroDto } from './intro.dto';
import { Type } from 'class-transformer';
import { MensaUser } from 'src/database/models/mensa-user.model';

export class MensaUserDto {

    @IsInt()
	mensaId: number;

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

    getMensaUser(membershipNumber: string): MensaUser {
        const mensaUser = new MensaUser();
        mensaUser.membershipNumber = membershipNumber;
        mensaUser.mensaId = this.mensaId;
        mensaUser.cooks = this.volunteerCook;
        mensaUser.dishwasher = this.volunteerDishwasher;
        mensaUser.allergies = this.allergies;
        mensaUser.extraInfo = this.extraInfo;
        mensaUser.confirmed = false;
        mensaUser.paid = 0;
        mensaUser.vegetarian = this.isVegetarian;
        mensaUser.isIntro = false;
        return mensaUser;
    }

    getIntroMensaUser(membershipNumber: string): MensaUser {
        if (this.intro == null) {
            throw new Error('Intro is not set!');
        }

        const mensaUser = this.getMensaUser(membershipNumber);
        mensaUser.isIntro = true;
        mensaUser.vegetarian = this.intro.isVegetarian;
        mensaUser.extraInfo = this.intro.extraInfo;
        mensaUser.allergies = this.intro.allergies;
        return mensaUser;
    }
}
