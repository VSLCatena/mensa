import Result, {Success, Failure, runCatching} from "../../domain/common/utils/Result";

export function requireNotNull<T>(argName: string, arg: T|null|undefined): T {
    if (arg === null || arg === undefined) throw Error("non-nullable argument '"+argName+"' is null");

    return arg;
}

export function checkIsArray(argName: string, arg: unknown) {
    if (!Array.isArray(arg))
        throw new Error(`${argName} is not of type Array. (${typeof arg})`);
}


interface Mapper <I,O> {
    (input: I): O;
}
