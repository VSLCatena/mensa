import { Test, TestingModule } from '@nestjs/testing';
import { MensaUserExtraOptionRepository } from './mensa-user-extra-option-repository';

describe('MensaUserExtraOptionRepository', () => {
  let provider: MensaUserExtraOptionRepository;

  beforeEach(async () => {
    const module: TestingModule = await Test.createTestingModule({
      providers: [MensaUserExtraOptionRepository],
    }).compile();

    provider = module.get<MensaUserExtraOptionRepository>(MensaUserExtraOptionRepository);
  });

  it('should be defined', () => {
    expect(provider).toBeDefined();
  });
});
