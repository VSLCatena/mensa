import {injectable} from 'tsyringe';
import {FoodOptionMapper} from '../../common/mapper/FoodOptionMapper';
import {UnparsedFullUser} from '../schema/UnparsedFullUserSchema';
import {FullUser} from '../../../domain/common/model/User';

@injectable()
export class UnparsedFullUserMapper {
  constructor(private readonly foodOptionMapper: FoodOptionMapper) {}

  async map(data: UnparsedFullUser): Promise<FullUser> {
    const foodOption =
      data.foodPreference !== null
        ? await this.foodOptionMapper.map(data.foodPreference)
        : null;

    return {
      ...data,
      foodPreference: foodOption,
    };
  }
}
