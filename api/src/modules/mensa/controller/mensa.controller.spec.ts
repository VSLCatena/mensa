import { Test, TestingModule } from '@nestjs/testing';
import { MensaController } from './mensa.controller';

describe('MensaController', () => {
	let controller: MensaController;

	beforeEach(async () => {
		const module: TestingModule = await Test.createTestingModule({
			controllers: [MensaController]
		}).compile();

		controller = module.get<MensaController>(MensaController);
	});

	it('should be defined', () => {
		expect(controller).toBeDefined();
	});
});
