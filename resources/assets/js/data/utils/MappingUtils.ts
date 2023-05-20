export function requireNotNull<T>(
  argName: string,
  arg: T | null | undefined
): T {
  if (arg === null || arg === undefined)
    throw new Error("non-nullable argument '" + argName + "' is null");

  return arg;
}

export function requireStringProp<T>(object: T, prop: keyof T): string {
  requireProperty(object, prop);

  const value = object[prop];
  if (typeof value !== 'string') {
    throw new Error(
      `${String(
        prop
      )} was expected to be of type string, but was ${typeof value} instead`
    );
  }
  return value;
}

export function requireProperty<T>(object: T, prop: keyof T): void {
  if (object[prop] === undefined || object[prop] === null) {
    throw new Error(`${String(prop)} is required but wasn't present in object`);
  }
}

export function checkIsArray(argName: string, arg: unknown): void {
  if (!Array.isArray(arg)) {
    throw new Error(`${argName} is not of type Array. (${typeof arg})`);
  }
}
