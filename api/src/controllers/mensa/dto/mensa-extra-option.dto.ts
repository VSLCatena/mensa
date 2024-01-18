import { IsNumber, IsString } from 'class-validator';

export class MensaExtraOptionDto {
  @IsNumber()
  id: number;

  @IsNumber()
  mensaId: number;

  @IsString()
  description: string;

  @IsNumber()
  price: number;
}