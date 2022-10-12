export function requireNotNull<T>(argName: string, arg: T | null | undefined): T {
    if (arg === null || arg === undefined) throw new Error("non-nullable argument '" + argName + "' is null");

    return arg;
}

export function requireStringProp<T>(object: T, prop: keyof T): string {
    requireProperty(object, prop);

    const value = object[prop];
    if (typeof value !== 'string') {
        throw new Error(`${String(prop)} was expected to be of type string, but was ${typeof value} instead`);
    }
    return value;
}

export function requireNumberProp<T>(object: T, prop: keyof T): number {
    requireProperty(object, prop);

    const value = object[prop];
    if (typeof value !== 'number') {
        throw new Error(`${String(prop)} was expected to be of type number, but was ${typeof value} instead`);
    }
    return value;
}

export function requireBooleanProp<T>(object: T, prop: keyof T): boolean {
    requireProperty(object, prop);

    const value = object[prop];
    if (typeof value !== 'boolean') {
        throw new Error(`${String(prop)} was expected to be of type boolean, but was ${typeof value} instead`);
    }
    return value;
}

export function requireProperty<T>(object: T, prop: keyof T) {
    if (object[prop] === undefined || object[prop] === null) {
        throw new Error(`${String(prop)} is required but wasn't present in object`);
    }
}

export function checkIsArray(argName: string, arg: unknown) {
    if (!Array.isArray(arg))
        throw new Error(`${argName} is not of type Array. (${typeof arg})`);
}


interface Mapper<I, O> {
    (input: I): O;
}
