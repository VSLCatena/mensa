import {injectable} from 'tsyringe';

@injectable()
export class DateMapper {
  map(num: number): Date {
    return new Date(num * 1000);
  }
}
