import { Test, TestingModule } from '@nestjs/testing';
import { MensaExtraOptionRepository } from './mensa-extra-option-repository';

describe('MensaExtraOptionRepository', () => {
  let provider: MensaExtraOptionRepository;

  beforeEach(async () => {
    const module: TestingModule = await Test.createTestingModule({
      providers: [MensaExtraOptionRepository],
    }).compile();

    provider = module.get<MensaExtraOptionRepository>(MensaExtraOptionRepository);
  });

  it('should be defined', () => {
    expect(provider).toBeDefined();
  });
});
