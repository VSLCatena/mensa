import { FormGroup } from "@angular/forms";
import { IntroDto } from "./intro-dto";

export class MensaUserDto {

	mensaId: number = 0;
    isVegetarian: boolean = false;
    allergies: string = '';
    extraInfo: string = '';
	intro: IntroDto | null = null;
    volunteerDishwasher: boolean = false;
    volunteerCook: boolean = false;

    mapForm(form: FormGroup): void {
        this.mensaId = form.get('mensaId')!.value;
        this.isVegetarian = form.get('isVegetarian')!.value;
        this.allergies = form.get('allergies')!.value;
        this.extraInfo = form.get('extraInfo')!.value;
        this.volunteerDishwasher = form.get('volunteerDishwasher')!.value;
        this.volunteerCook = form.get('volunteerCook')!.value;
        if (form.get('withIntro')!.value) {
            this.intro = new IntroDto(
                form.get('intro')!.value.isVegetarian,
                form.get('intro')!.value.allergies,
                form.get('intro')!.value.extraInfo
            );
        }
    }
}


