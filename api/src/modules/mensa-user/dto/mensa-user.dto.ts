import { IntroDto } from './intro.dto';

export class MensaUserDto {
	mensaId: string;
    isVegetarian: boolean;
    allergies: string;
    extraInfo: string;
	intro: IntroDto | null;
    volunteerDishwasher: boolean;
    volunteerCook: boolean;
}
