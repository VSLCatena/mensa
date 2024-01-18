import { Test, TestingModule } from '@nestjs/testing';
import { MensaExtraOptionService } from './mensa-extra-option.service';

describe('MensaExtraOptionService', () => {
  let provider: MensaExtraOptionService;

  beforeEach(async () => {
    const module: TestingModule = await Test.createTestingModule({
      providers: [MensaExtraOptionService],
    }).compile();

    provider = module.get<MensaExtraOptionService>(MensaExtraOptionService);
  });

  it('should be defined', () => {
    expect(provider).toBeDefined();
  });
});
