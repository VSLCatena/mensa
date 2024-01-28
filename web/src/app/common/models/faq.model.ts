export class Faq {
	id: number;
	question: string;
	answer: string;
	lastEditedBy: string;
	createdAt: Date;
	updatedAt: Date;

    constructor(
        id: number,
        question: string,
        answer: string,
        lastEditedBy: string,
        createdAt: Date,
        updatedAt: Date
    ) {
        this.id = id;
        this.question = question;
        this.answer = answer;
        this.lastEditedBy = lastEditedBy;
        this.createdAt = createdAt;
        this.updatedAt = updatedAt;
    }
}
