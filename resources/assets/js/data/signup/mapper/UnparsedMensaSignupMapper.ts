import {injectable} from 'tsyringe';
import {DateMapper} from '../../common/mapper/DateMapper';
import {FoodOptionMapper} from '../../common/mapper/FoodOptionMapper';
import {MensaSignup} from '../../../domain/signup/model/MensaSignup';
import {UnparsedMensaSignup} from '../schema/UnparsedMensaSignupSchema';

@injectable()
export class UnparsedMensaSignupMapper {
  constructor(
    private readonly dateMapper: DateMapper,
    private readonly foodOptionMapper: FoodOptionMapper
  ) {}

  map(data: UnparsedMensaSignup): MensaSignup {
    const foodOption =
      data.foodOption !== null
        ? this.foodOptionMapper.map(data.foodOption)
        : null;

    return {
      ...data,
      foodOption,
    };
  }
}
