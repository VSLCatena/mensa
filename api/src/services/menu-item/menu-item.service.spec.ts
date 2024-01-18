import { Test, TestingModule } from '@nestjs/testing';
import { MenuItemService } from './menu-item.service';

describe('MenuItemService', () => {
  let provider: MenuItemService;

  beforeEach(async () => {
    const module: TestingModule = await Test.createTestingModule({
      providers: [MenuItemService],
    }).compile();

    provider = module.get<MenuItemService>(MenuItemService);
  });

  it('should be defined', () => {
    expect(provider).toBeDefined();
  });
});
