export class InvalidSchemaError extends Error {
  constructor(public schemaErrors: unknown) {
    super();
  }
}
