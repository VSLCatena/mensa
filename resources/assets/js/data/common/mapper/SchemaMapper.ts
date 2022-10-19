import {injectable} from 'tsyringe';
import Ajv from 'ajv';
import {JSONSchemaType} from 'ajv/lib/types/json-schema';
import {Schema} from 'ajv/lib/types';
import {InvalidSchemaError} from '../../../domain/common/errors/InvalidSchemaError';

@injectable()
export class SchemaMapper {
  map<T>(schema: Schema | JSONSchemaType<T>, data: any): T {
    const validate = new Ajv({
      removeAdditional: true,
      coerceTypes: true,
      useDefaults: true,
    }).compile<T>(schema);

    if (validate(data)) {
      return data as T;
    } else {
      throw new InvalidSchemaError(validate.errors);
    }
  }
}
