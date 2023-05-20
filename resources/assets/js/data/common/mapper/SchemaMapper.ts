import {injectable} from 'tsyringe';
import Ajv, {JSONSchemaType, Schema} from 'ajv';
import {InvalidSchemaError} from '../../../domain/common/errors/InvalidSchemaError';

@injectable()
export class SchemaMapper {
  private ajv = new Ajv({
    removeAdditional: true,
    coerceTypes: true,
    useDefaults: true,
  });

  map<T>(schema: Schema | JSONSchemaType<T>, data: any): T {
    const validate = this.ajv.compile<T>(schema);

    if (validate(data)) {
      return data as T;
    } else {
      console.error(validate.errors);
      throw new InvalidSchemaError(validate.errors);
    }
  }

  safeMap<T>(schema: Schema | JSONSchemaType<T>, data: any): T | null {
    try {
      return this.map(schema, data);
    } catch (e) {
      return null;
    }
  }
}
