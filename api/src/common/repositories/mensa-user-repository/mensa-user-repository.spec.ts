import { Test, TestingModule } from '@nestjs/testing';
import { MensaUserRepository } from './mensa-user-repository';

describe('MensaUserRepository', () => {
  let provider: MensaUserRepository;

  beforeEach(async () => {
    const module: TestingModule = await Test.createTestingModule({
      providers: [MensaUserRepository],
    }).compile();

    provider = module.get<MensaUserRepository>(MensaUserRepository);
  });

  it('should be defined', () => {
    expect(provider).toBeDefined();
  });
});
