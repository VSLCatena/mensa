export class MenuItem {
	id: number;
	mensaId: number;
	order: number;
	text: string;

	constructor(
		id: number,
		mensaId: number,
		order: number,
		description: string,
	) {
        this.id = id;
        this.mensaId = mensaId;
        this.order = order;
        this.text = description;
	}
}