export class MenuItem {
	id: number | null;
	mensaId: number | null;
	order: number;
	text: string;

	constructor(
		id: number | null,
		mensaId: number | null,
		order: number,
		description: string
	) {
		this.id = id;
		this.mensaId = mensaId;
		this.order = order;
		this.text = description;
	}
}
