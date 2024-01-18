import { Test, TestingModule } from '@nestjs/testing';
import { MensaUserService } from './mensa-user.service';

describe('MensaUserService', () => {
  let provider: MensaUserService;

  beforeEach(async () => {
    const module: TestingModule = await Test.createTestingModule({
      providers: [MensaUserService],
    }).compile();

    provider = module.get<MensaUserService>(MensaUserService);
  });

  it('should be defined', () => {
    expect(provider).toBeDefined();
  });
});
