import { IsString } from 'class-validator';
import { StaffRole } from 'src/common/types/staff-role.type';

export class StaffDto {
  @IsString()
  name: string;

  @IsString()
  role: StaffRole;
}