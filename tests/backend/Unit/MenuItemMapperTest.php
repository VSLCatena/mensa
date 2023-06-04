<?php

namespace Tests\backend\Unit;

use App\Http\Controllers\Api\v1\Mensa\Mappers\MenuItemMapper;
use App\Models\MenuItem;
use Tests\TestCase;

class MenuItemMapperTest extends TestCase
{
    private readonly MenuItemMapper $menuItemMapper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->menuItemMapper = new MenuItemMapper();
    }

    public function test_if_a_menu_item_is_mapped_correctly()
    {
        // Arrange
        $menuItem = MenuItem::factory()->make([
            'id' => 'id',
            'text' => 'text'
        ]);

        // Act
        $result = $this->menuItemMapper->map($menuItem);

        // Assert
        $this->assertEquals('id', $result->id);
        $this->assertEquals('text', $result->text);
    }

    public function test_if_an_array_is_correctly_mapped() {
        // Arrange
        $menuItem1 = MenuItem::factory()->make([
            'id' => 'id1',
            'text' => 'text1',
            'order' => 1
        ]);
        $menuItem2 = MenuItem::factory()->make([
            'id' => 'id2',
            'text' => 'text2',
            'order' => 2
        ]);

        // Act
        $result = $this->menuItemMapper->mapArray([$menuItem2, $menuItem1]);

        // Assert
        $this->assertEquals('id1', $result[0]->id);
        $this->assertEquals('text1', $result[0]->text);
        $this->assertEquals('id2', $result[1]->id);
        $this->assertEquals('text2', $result[1]->text);
    }
}
