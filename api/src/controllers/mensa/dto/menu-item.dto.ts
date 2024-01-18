import { IsNumber, IsString } from 'class-validator';

export class MenuItemDto {
  @IsNumber()
  id: number;

  @IsNumber()
  mensaId: number;

  @IsNumber()
  order: number;

  @IsString()
  description: string;
}