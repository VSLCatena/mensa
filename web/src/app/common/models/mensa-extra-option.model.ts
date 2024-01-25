export class MensaExtraOption {
	id: number;
	mensaId: number;
	description: string;
	price: number;

	constructor(
		id: number,
		mensaId: number,
		description: string,
		price: number
	) {
		this.id = id;
		this.mensaId = mensaId;
		this.description = description;
		this.price = price;
	}
}
