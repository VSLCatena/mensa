import { Test, TestingModule } from '@nestjs/testing';
import { MensaUserService } from './mensa-user.service';

describe('MensaUserService', () => {
  let service: MensaUserService;

  beforeEach(async () => {
    const module: TestingModule = await Test.createTestingModule({
      providers: [MensaUserService],
    }).compile();

    service = module.get<MensaUserService>(MensaUserService);
  });

  it('should be defined', () => {
    expect(service).toBeDefined();
  });
});
