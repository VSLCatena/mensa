import { Model, ModelCtor } from 'sequelize-typescript';
import { Injectable, NotFoundException } from '@nestjs/common';
import { FindOptions, UpdateOptions, DestroyOptions } from 'sequelize';

@Injectable()
export class BaseRepository<T extends Model<T>> {
  constructor(private readonly model: ModelCtor<T>) { }

  async findAll(options?: FindOptions<T>): Promise<T[]> {
    return this.model.findAll(options);
  }

  async findById(id: number, options?: FindOptions<T>): Promise<T> {
    const instance = await this.model.findByPk(id, options);
    if (!instance) {
      throw new NotFoundException(`Entity with id ${id} not found`);
    }
    return instance;
  }

  async create(entity: T): Promise<T> {
    return entity.save();
  }

  async update(id: number, entity: Partial<T>): Promise<void> {
    const [affectedCount] = await this.model.update(entity, {
      where: { id },
      returning: true,
    } as UpdateOptions);


    if (affectedCount === 0) {
      throw new NotFoundException(`Entity with id ${id} not found`);
    }

    return Promise.resolve();
  }

  async delete(id: number): Promise<number> {
    const deletedCount = await this.model.destroy({
      where: { id },
    } as DestroyOptions);

    if (deletedCount === 0) {
      throw new NotFoundException(`Entity with id ${id} not found`);
    }

    return deletedCount;
  }
}
