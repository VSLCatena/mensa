import { Test, TestingModule } from '@nestjs/testing';
import { MensaRepository } from './mensa-repository';

describe('MensaRepository', () => {
  let provider: MensaRepository;

  beforeEach(async () => {
    const module: TestingModule = await Test.createTestingModule({
      providers: [MensaRepository],
    }).compile();

    provider = module.get<MensaRepository>(MensaRepository);
  });

  it('should be defined', () => {
    expect(provider).toBeDefined();
  });
});
