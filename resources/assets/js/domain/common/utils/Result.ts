
type Result<T> = Success<T> | Failure<T>
export default Result

abstract class ResultType<T> {

    public isSuccess(): boolean {
        return this instanceof Success
    }

    public isFailure(): boolean {
        return this instanceof Failure
    }

    public getOrThrow(): T {
        if (this instanceof Success) {
            return this.value;
        } else {
            throw (this as Failure<T>).error;
        }
    }

    public getOrNull(): T|null {
        if (this instanceof Success) {
            return this.value;
        }

        return null;
    }
}

export function runCatching<T>(call: () => T): Result<T> {
    try {
        return new Success(call());
    } catch (e) {
        return new Failure(e as Error);
    }
}

export class Success<T> extends ResultType<T> {
    constructor(public value: T) {
        super()
    }
}

export class Failure<T> extends ResultType<T> {
    constructor(public error?: Error) {
        super()
    }
}