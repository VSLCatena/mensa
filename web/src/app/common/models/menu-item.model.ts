export class MenuItem {
	id: number;
	mensaId: number;
	order: number;
	description: string;

	constructor(
		id: number,
		mensaId: number,
		order: number,
		description: string,
	) {
        this.id = id;
        this.mensaId = mensaId;
        this.order = order;
        this.description = description;
	}
}