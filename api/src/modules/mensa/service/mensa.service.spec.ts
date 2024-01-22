import { Test, TestingModule } from '@nestjs/testing';
import { MensaService } from './mensa.service';

describe('MensaService', () => {
  let service: MensaService;

  beforeEach(async () => {
    const module: TestingModule = await Test.createTestingModule({
      providers: [MensaService],
    }).compile();

    service = module.get<MensaService>(MensaService);
  });

  it('should be defined', () => {
    expect(service).toBeDefined();
  });
});
