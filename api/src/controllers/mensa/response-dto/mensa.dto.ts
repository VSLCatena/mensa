import { IsBoolean, IsDate, IsNumber, IsString, ValidateNested } from 'class-validator';
import { Type } from 'class-transformer';
import { MenuItemDto } from './menu-item.dto';
import { MensaExtraOptionDto } from './mensa-extra-option.dto';
import { StaffDto } from './staff.dto';

export class MensaDto {
  @IsNumber()
  id: number;

  @IsString()
  title: string;

  @IsDate()
  date: Date;

  @IsDate()
  closingTime: Date;

  @IsNumber()
  maxUsers: number;

  @IsNumber()
  enrollments: number;

  @IsNumber()
  price: number;

  @IsBoolean()
  closed: boolean;

  @ValidateNested({ each: true })
  @Type(() => MenuItemDto)
  menuItems: MenuItemDto[];

  @ValidateNested({ each: true })
  @Type(() => MensaExtraOptionDto)
  extraOptions: MensaExtraOptionDto[];

  @ValidateNested({ each: true })
  @Type(() => StaffDto)
  staff: StaffDto[];

  @IsDate()
  createdAt: Date;

  @IsDate()
  updatedAt: Date;
}