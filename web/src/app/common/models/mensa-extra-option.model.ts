export class MensaExtraOption {
	id: number | null;
	mensaId: number | null;
	description: string;
	price: number;

	constructor(
		id: number | null,
		mensaId: number | null,
		description: string,
		price: number
	) {
		this.id = id;
		this.mensaId = mensaId;
		this.description = description;
		this.price = price;
	}
}
