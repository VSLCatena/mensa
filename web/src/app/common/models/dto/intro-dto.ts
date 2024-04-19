export class IntroDto {
    isVegetarian: boolean;
    allergies: string;
    extraInfo: string;

    constructor(
        isVegetarian: boolean,
        allergies: string,
        extraInfo: string
    ) {
        this.isVegetarian = isVegetarian;
        this.allergies = allergies;
        this.extraInfo = extraInfo;
    }
}