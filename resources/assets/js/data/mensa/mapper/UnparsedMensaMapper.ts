import {Mensa} from '../../../domain/mensa/model/Mensa';
import {injectable} from 'tsyringe';
import {DateMapper} from '../../common/mapper/DateMapper';
import {FoodOptionMapper} from '../../common/mapper/FoodOptionMapper';
import {UnparsedMensaList} from '../schema/MensaListSchema';
import {MensaList} from '../../../domain/mensa/model/MensaList';
import {UnparsedMensa} from '../schema/UnparsedMensaSchema';

@injectable()
export class UnparsedMensaMapper {
  constructor(
    private readonly dateMapper: DateMapper,
    private readonly foodOptionMapper: FoodOptionMapper
  ) {}

  async mapList(data: UnparsedMensaList): Promise<MensaList> {
    return {
      between: {
        start: this.dateMapper.map(data.between.start),
        end: this.dateMapper.map(data.between.end),
      },
      mensas: await Promise.all(data.mensas.map(mensa => this.map(mensa))),
    };
  }

  async map(data: UnparsedMensa): Promise<Mensa> {
    return {
      ...data,
      foodOptions: await this.foodOptionMapper.mapList(data.foodOptions),
      date: this.dateMapper.map(data.date),
      closingTime: this.dateMapper.map(data.date),
    };
  }
}
