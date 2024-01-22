import { Test, TestingModule } from '@nestjs/testing';
import { MensaProvider } from './mensa-provider';

describe('MensaProvider', () => {
  let provider: MensaProvider;

  beforeEach(async () => {
    const module: TestingModule = await Test.createTestingModule({
      providers: [MensaProvider],
    }).compile();

    provider = module.get<MensaProvider>(MensaProvider);
  });

  it('should be defined', () => {
    expect(provider).toBeDefined();
  });
});
