import { Test, TestingModule } from '@nestjs/testing';
import { MensaUserController } from './mensa-user.controller';

describe('MensaUserController', () => {
  let controller: MensaUserController;

  beforeEach(async () => {
    const module: TestingModule = await Test.createTestingModule({
      controllers: [MensaUserController],
    }).compile();

    controller = module.get<MensaUserController>(MensaUserController);
  });

  it('should be defined', () => {
    expect(controller).toBeDefined();
  });
});
