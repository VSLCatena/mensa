import { Test, TestingModule } from '@nestjs/testing';
import { MenuItemRepository } from './menu-item-repository';

describe('MenuItemRepository', () => {
  let provider: MenuItemRepository;

  beforeEach(async () => {
    const module: TestingModule = await Test.createTestingModule({
      providers: [MenuItemRepository],
    }).compile();

    provider = module.get<MenuItemRepository>(MenuItemRepository);
  });

  it('should be defined', () => {
    expect(provider).toBeDefined();
  });
});
