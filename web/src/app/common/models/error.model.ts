export class Error {
	isError: boolean = false;
	message: string = 'Er was een netwerkfout';

	constructor() {}

	public setError(isError: boolean, message: string): void {
		this.isError = isError;
		if (message !== '') this.message = message;
	}
}
