import { IntroDto } from "./intro-dto";

export class MensaUserDto {

	mensaId: number;
    isVegetarian: boolean;
    allergies: string;
    extraInfo: string;
	intro: IntroDto | null;
    volunteerDishwasher: boolean;
    volunteerCook: boolean;

    constructor(
        mensaId: number,
        isVegetarian: boolean,
        allergies: string,
        extraInfo: string,
        intro: IntroDto | null,
        volunteerDishwasher: boolean,
        volunteerCook: boolean
    ) {
        this.mensaId = mensaId;
        this.isVegetarian = isVegetarian;
        this.allergies = allergies;
        this.extraInfo = extraInfo;
        this.intro = intro;
        this.volunteerDishwasher = volunteerDishwasher;
        this.volunteerCook = volunteerCook;
    }
}


