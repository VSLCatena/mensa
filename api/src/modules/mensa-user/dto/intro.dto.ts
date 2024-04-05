import { IsBoolean, IsString } from "class-validator";

export class IntroDto {

    @IsBoolean()
    isVegetarian: boolean;

    @IsString()
    allergies: string;

    @IsString()
    extraInfo: string;
}