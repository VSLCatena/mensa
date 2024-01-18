import { Test, TestingModule } from '@nestjs/testing';
import { MensaService } from './mensa.service';

describe('MensaService', () => {
  let provider: MensaService;

  beforeEach(async () => {
    const module: TestingModule = await Test.createTestingModule({
      providers: [MensaService],
    }).compile();

    provider = module.get<MensaService>(MensaService);
  });

  it('should be defined', () => {
    expect(provider).toBeDefined();
  });
});
