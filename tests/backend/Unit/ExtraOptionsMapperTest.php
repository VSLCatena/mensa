<?php

namespace Tests\backend\Unit;

use App\Http\Controllers\Api\v1\Mensa\Mappers\ExtraOptionsMapper;
use App\Models\ExtraOption;
use Tests\TestCase;

class ExtraOptionsMapperTest extends TestCase
{
    private readonly ExtraOptionsMapper $extraOptionsMapper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->extraOptionsMapper = new ExtraOptionsMapper();
    }

    public function testMapExtraOption()
    {
        // Arrange
        $extraOption = ExtraOption::factory()->make([
            'id' => 'id',
            'description' => 'description',
            'order' => 1,
            'price' => 1.0,
        ]);

        // Act
        $result = $this->extraOptionsMapper->map($extraOption);

        // Assert
        $this->assertEquals('id', $result->id);
        $this->assertEquals('description', $result->description);
        $this->assertEquals(1.0, $result->price);
    }

    public function testMapExtraOptions()
    {
        // Arrange
        $extraOptions = [
            ExtraOption::factory()->make([
                'id' => 'id1',
                'description' => 'description1',
                'order' => 1,
                'price' => 1.0,
            ]),
            ExtraOption::factory()->make([
                'id' => 'id2',
                'description' => 'description2',
                'order' => 2,
                'price' => 2.0,
            ]),
            ExtraOption::factory()->make([
                'id' => 'id3',
                'description' => 'description3',
                'order' => 3,
                'price' => 3.0,
            ]),
        ];

        // Act
        $result = $this->extraOptionsMapper->mapArray($extraOptions);

        // Assert
        $this->assertEquals('id1', $result[0]->id);
        $this->assertEquals('description1', $result[0]->description);
        $this->assertEquals(1.0, $result[0]->price);

        $this->assertEquals('id2', $result[1]->id);
        $this->assertEquals('description2', $result[1]->description);
        $this->assertEquals(2.0, $result[1]->price);

        $this->assertEquals('id3', $result[2]->id);
        $this->assertEquals('description3', $result[2]->description);
        $this->assertEquals(3.0, $result[2]->price);
    }

}
